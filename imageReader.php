<?php


$im = file_get_contents($_GET['image']);

header("Content-type: image/jpeg");
echo $im;
?>