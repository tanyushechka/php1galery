<?php
require 'functions.php';
require 'functionsBD.php';
session_start();


$connect = mysql_connect('localhost','root','');
mysql_select_db('php1test');


// updateComment(mysql_real_escape_string($_SESSION['img']), mysql_real_escape_string($_SESSION['comment']));  // правка комментария из ДЗ №6

// insertImageToBase(                                            // информацию о новой загруженной картинке записываем в БД
//     mysql_real_escape_string($_SESSION['name']),              //пока с кавычками не получается
//     mysql_real_escape_string($_SESSION['date']),
//     mysql_real_escape_string($_SESSION['path']),
//     mysql_real_escape_string($_SESSION['size_px']),
//     mysql_real_escape_string($_SESSION['size_mb']),
//     '');

// unset($_SESSION['name']);
// unset($_SESSION['date']);
// unset($_SESSION['path']);
// unset($_SESSION['size_px']);
// unset($_SESSION['size_mb']);

$query = 'SELECT * FROM images WHERE name <> "" ORDER BY date DESC';
$res = mysql_query($query);

$arrImages = createQueryArray($res);   // формируем массив для загрузки галереи

?>


<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Main page</title>
    <link type="text/css" rel="stylesheet" href="style.css"/>
</head>
<body>


<form id="loadForm" action="load.php" method="post" enctype="multipart/form-data" name="uploadform">
    <fieldset>
        <legend><strong>Загрузите файл</strong></legend>
        <input type="file" form="loadForm" name="image"/>
        <input type="submit" form="loadForm" name="load" value="Загрузить"/>
        <label class="redlabel"><?php echo $_SESSION['error'];
            unset($_SESSION['error']); ?></label><br><br>

    </fieldset>
</form>

<!-- форма правки комментария из ДЗ №6  -->
<form id="edit" action="comment.php" method="post" enctype="multipart/form-data" name="editcomment">
    <fieldset>
        <legend><strong>Измените описание файла &nbsp;<?php echo $_SESSION['img'];  ?>&nbsp;</strong></legend>
        <textarea form="edit" name="comments" cols="170" rows="3"><?php echo $_SESSION['comment']; unset($_SESSION['comment']); ?></textarea><br/>
        <input type="submit" form="edit" value="Сохранить исправления"/>
    </fieldset>
</form>


<br>
<label class="redlabel">Чтобы открыть описание картинки, нажмите на неё, а для просмотра деталей - перейдите по ссылке</label>
<br><br>
<form>
            <textarea name="editor1" id="editor1" rows="10" cols="80">

            </textarea>

</form>
<?php
if ($arrImages) {
foreach ($arrImages as $key => $el) {
    echo '
    <fieldset class="image">
        <legend><strong><a href="details.php?id='.$arrImages[$key]['id'].'">'. $arrImages[$key]['name'] . '</a></strong></legend>';
    echo '<input type="image" form="edit" name="edit" value="' . $arrImages[$key]['name'] . '" src="' . $arrImages[$key]['path'] . '"  height="160" width="210" title="' . $arrImages[$key]['name'] . '" />';
    echo '</fieldset>';
}
}
?>
</body>
</html>
