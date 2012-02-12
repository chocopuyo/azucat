<?php
$file_path = '/home'.$_SERVER['PATH_INFO'];
preg_match('/[^\/]+$/',$file_path,$file_name);
header('Content-Type: application/octet-stream'); 
header('Content-Disposition: attachment; filename="'.$file_name[0].'"'); 
readfile($file_path);
?>
