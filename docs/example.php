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

require_once 'XML/CSSML.php';
error_reporting(E_ALL);
$cssml = '<cssml:CSSML xmlns:cssml="http://pear.php.net/cssml/1.0">
  <style browserInclude="not(gecko)" filterInclude="admin">
    <selector>p</selector>
    <declaration property="color">blue</declaration>
  </style>
</cssml:CSSML>';
$cssml = & XML_CSSML::factory('libxslt', $cssml, 'string', array('browser' => 'ns4', 'filter' => 'admin', 'comment' => 'hello there!', 'output' => 'foo.css'));
if (PEAR::isError($cssml)) {
    die($cssml->getMessage().' / '.$cssml->getUserInfo()."\n");
}
//var_dump($cssml);
$OK = $cssml->process();
//var_dump($OK);
?>
