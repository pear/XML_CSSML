<?php
/**
 * XML_CSSML
 *
 * PHP version 4.3.0
 *
 * Copyright (c) 1997-2003 The PHP Group
 *
 * This source file is subject to version 2.0 of the PHP license,
 * that is bundled with this package in the file LICENSE, and is
 * available at through the world-wide-web at
 * http://www.php.net/license/2_02.txt.
 * If you did not receive a copy of the PHP license and are unable to
 * obtain it through the world-wide-web, please send a note to
 * license@php.net so we can mail you a copy immediately.
 *
 * Authors:
 *
 * @category XML
 * @package  XML_CSSML
 * @author   Dan Allen <dan@mojavelinux.com>
 * @license  PHP 2.02 http://www.php.net/license/2_02.txt
 * @version  CVS: $Id$
 * @link     http://pear.php.net/package/XML_CSSML
 */

// {{{ description

// XML_CSSML is a CSSML to CSS xslt parser

// }}}

/**
 * The XML_CSSML_xslt is a container class which
 * provides the sablotron xsl functions to parse a CSSML
 * document into a stylesheet with the ability to output
 * to a file or return
 *
 * @category XML
 * @package  XML_CSSML
 * @author   Dan Allen <dan@mojavelinux.com>
 * @license  PHP 2.02 http://www.php.net/license/2_02.txt
 * @link     http://pear.php.net/package/XML_CSSML
 */
class XML_CSSML_xslt extends XML_CSSML
{
    // {{{ properties

    /**
     * The sabltron extension can use xml strings as arguments for the
     * processor, but must do so when calling xslt_process.  This variable
     * holds those parameters.
     * @var array $arguments
     */
    var $arguments = array();

    // }}}
    // {{{ constructor

    /**
     * Class constructor, prepare the cssml document object from either a string, file or object
     *
     * @param mixed $in_cssml  Optionally the CSSML data can be passed to the constructor
     * @param mixed $in_type   unknown
     * @param mixed $in_params unknown
     *
     * @return void
     * @access private
     */
    function XML_CSSML_xslt($in_cssml = null, $in_type = 'string', $in_params = null)
    {
        if (!function_exists('xslt_create')) {
            $this = PEAR::raiseError(null, XML_CSSML_ERROR, null, E_USER_ERROR,
             'This driver needs the xslt extension to run', 'XML_CSSML_Error', true);
            return;
        }
        $this->loaded = false;
        if (!is_null($in_cssml)) {
            $this->load($in_cssml, $in_type);
        }

        if (!is_null($in_params)) {
            $this->setParams($in_params);
        }

        $this->stylesheetDoc = dirname(__FILE__) . '/xslt.xsl';
    }

    // }}}
    // {{{ process()

    /**
     * Run the transformation on the CSSML document using the CSSML xsl stylesheet.  If
     * the output method is to a file, then the function will not return.  If the output
     * is set to STDOUT, the xml string will be returned (really the css document) after
     * some clean up of entities and domxml bugs have been fixed
     *
     * @return css string if output method is STDOUT, else void
     * @access public
     * @todo   I need some error checking in here
     */
    function process()
    {
        if (parent::isError($process = parent::process())) {
            return $process;
        }

        // Prepare the params for passing to the stylesheet
        $params = array(
            'filter'        => $this->filter,
            'browser'       => $this->browser,
            'comment'       => $this->comment,
        );

        $xh = xslt_create();

        $result = xslt_process($xh, $this->CSSMLDoc, $this->stylesheetDoc, null, $this->arguments, $params);

        if ($this->output != 'STDOUT') {
            $fp = fopen($this->output, 'w');
            fwrite($fp, $result);
            fclose($fp);
            $result = true;
        }

        return $result;
    }

    // }}}
    // {{{ load()

    /**
     * Prepare the CSSML document object from either a string, file or object.  This
     * will set the CSSMLDoc class variable which will be parsed by the xsl stylesheet
     * into a CSS stylesheet
     *
     * @param mixed  $in_CSSML unknown
     * @param string $in_type  unknown
     *
     * @return void
     * @todo  I need some more error checking in here
     */
    function load($in_CSSML, $in_type = 'string')
    {
        if (parent::isError($load = parent::load())) {
            return $load;
        }

        if ($in_type == 'file' && @file_exists($in_CSSML)) {
            $this->CSSMLDoc = $in_CSSML;
        } elseif ($in_type == 'string' && is_string($in_CSSML)) {
            $this->CSSMLDoc  = 'arg:/_xml';
            $this->arguments = array('/_xml' => $in_CSSML);
        } else {
            return PEAR::raiseError(null, XML_CSSML_INVALID_DATA, null, E_USER_WARNING, "Request data: $in_CSSML", 'XML_CSSML_Error', true);
        }

        $this->loaded = true;
    }

    // }}}
}
?>
