<?php
require 'functions.php';
require 'functionsBD.php';
session_start();
$connect = mysql_connect('localhost', 'root', '');
mysql_select_db('php1test');

$id = $_GET['id'];
$query = "SELECT * FROM images WHERE id = $id";
$result = mysql_query($query);
$details = createQueryArray($result);
?>

<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Main page</title>
    <link type="text/css" rel="stylesheet" href="style.css"/>
</head>
<body>
<div id="wrapper">
    <table>
        <tr>
            <td>Название файла</td>
            <th><?php echo $details[0]['name']; ?></th>
            <td>Дата добавления в галерею</td>
            <th><?php echo $details[0]['date']; ?></th>
        </tr>
        <tr>
            <td>Размер в пикселах</td>
            <th><?php echo $details[0]['size_px']; ?></thd>
            <td>Размер в мегабайтах</td>
            <th><?php echo $details[0]['size_mb']; ?></th>
        </tr>
    </table>
    <p><?php echo $details[0]['com']; ?></p>
    <img src="<?php echo $details[0]['path']; ?>">

</div>
</body>
</html>