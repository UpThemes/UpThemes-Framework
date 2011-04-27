<?php
$filename = $_GET['f'];
$export = $_GET['e'];
header('Content-type: application/txt');
header('Content-Disposition: attachment; filename="'.$filename.'.txt"');
echo $export;
?>