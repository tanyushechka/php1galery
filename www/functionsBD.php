<?php

function createQueryArray($res)  // получение массива из БД для загрузки галереи
{
    while (false !== ($row = mysql_fetch_array($res))) {
        $arrImages[] = ['id' => $row['id'], 'name' => $row['name'], 'date' => $row['date'], 'path' => $row['path'], 'size_px' => $row['size_px'],
            'size_mb' => $row['size_mb'], 'com' => $row['com']];
    }
    return $arrImages;
}

function insertImageToBase($name, $date, $path, $size_px, $size_mb, $about)   // записываем информацию в БД о новой загруженной картинке
{
    $query = "INSERT INTO images (name, date, path, size_px, size_mb, about)
VALUES ('$name', '$date', '$path', '$size_px', '$size_mb', '$about')";
    $newRow = mysql_query($query);
}

function updateComment($name, $about)  // правка комментария к файлу в БД
{
    $query = "UPDATE images SET about = '$about' WHERE name = '$name'";
    $res = mysql_query($query);
}

?>