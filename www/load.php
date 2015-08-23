<?php
require 'functions.php';
$targetpath = 'images';
loadFile($targetpath);
header('Location: index.php');
?>