<?php
require_once '../CSSML.php';
error_reporting(E_ALL);
$cssml = '<cssml:CSSML xmlns:cssml="http://pear.php.net/cssml/1.0">
  <style filterInclude="admin">
    <selector>p</selector>
    <declaration property="color">text</declaration>
  </style>
</cssml:CSSML>';
$cssml = new XML_CSSML('xslt', $cssml, array('filter' => 'admin', 'comment' => 'hello there!', 'output' => 'STDOUT'));
//print_r($cssml);
echo $cssml->process();
?>
