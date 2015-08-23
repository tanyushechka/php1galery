<?php

function isImage($f)  // проверка на картинку по расширению
{
    $ff = strtolower(pathinfo($f)['extension']);
    return (($ff == 'gif') || ($ff == 'jpg') || ($ff == 'jpeg') || ($ff == 'png'));
}

function isReadable($targetpach)  // читаемо ли
{
    return ((is_readable($targetpach)) && (filesize($targetpach) > 0));
}

function getImagesFromDir($targetpath)  // формирует массив для первоначального заполнения таблицы
{
    $files = scandir($targetpath);
    $files = array_filter($files, function ($a) {
        return !is_dir($a);
    });
    $files = array_filter($files, function ($f) {
        return isImage($f);
    });
    foreach ($files as $f) {
        $path = $targetpath . DIRECTORY_SEPARATOR . $f;

        if (!isReadable($targetpath . DIRECTORY_SEPARATOR . $f) || ('' == getimagesize($path)[3])) {
            continue;
        }
        $px = getimagesize($path)[3];
        $mb = filesize($path);
        $com = explodeImg($path, 1);
        $time = date("d.m.y H:i:s", filectime($path));
        $arrImg[$f] = ['time' => $time, 'path' => $path, 'px' => $px, 'mb' => $mb, 'com' => $com];
    }
    return $arrImg;
}


function loadFile($targetpath)  //  загрузка новой картинки в нужный каталог
{
    $newName = $targetpath . DIRECTORY_SEPARATOR . basename($_FILES['image']['name']);
    switch (true) {
        case !is_uploaded_file($_FILES['image']['tmp_name']) :
            switch ($_FILES['image']['error']) {
                case 1:
                    $_SESSION['error'] = 'UPLOAD_ERR_INI_SIZE';
                    break;
                case 2:
                    $_SESSION['error'] = 'UPLOAD_ERR_FORM_SIZE';
                    break;
                case 3:
                    $_SESSION['error'] = 'UPLOAD_ERR_PARTIAL';
                    break;
                case 4:
                    $_SESSION['error'] = 'UPLOAD_ERR_NO_FILE';
                    break;
                case 6:
                    $_SESSION['error'] = 'UPLOAD_ERR_NO_TMP_DIR';
                    break;
                case 7:
                    $_SESSION['error'] = 'UPLOAD_ERR_CANT_WRITE';
                    break;
                case 8:
                    $_SESSION['error'] = 'UPLOAD_ERR_EXTENSION';
                    break;
            }
            break;

        case file_exists($newName) :
            $_SESSION['error'] = 'FILE_EXISTS';
            break;

        case !isImage($_FILES['image']['name']) :
            $_SESSION['error'] = 'NOT_AN_IMAGE';
            break;

        case !isReadable($_FILES['image']['tmp_name']) || ('' == getimagesize($_FILES['image']['tmp_name'])[3]) :
            $_SESSION['error'] = 'NOT_FOR_READ';
            break;

        case move_uploaded_file($_FILES['image']['tmp_name'], $newName) :
            $_SESSION['name'] = basename($_FILES['image']['name']);
            $_SESSION['date'] = date("d.m.y H:i:s", filectime($newName));
            $_SESSION['path'] = $newName;
            $_SESSION['size_px'] = getimagesize($newName)[3];
            $_SESSION['size_mb'] = filesize($newName);

            header('Location: index.php');

        default :
            $_SESSION['error'] = 'UNKNOWN_ERROR';
            break;
    }

}

///////////////////////////////////////////////////////

function explodeImg($f, $mode)  //  функция служит для расчленения файла на собственно картинку и её описание - из ДЗ №6
{
    $content = file_get_contents($f);
    $arr = explode('%%%', $content);
    switch ($mode) {
        case 0 :
            return $arr[0];  // вычленненое изображение
        case 1 :
            if (($c = count($arr) - 1) == 0) {
                return 'no_comments';  // вычленненый комментарий - нет в наличии
            } else {
                $i = 1;
                while ($i <= $c) {
                    $com = $com . $arr[$i];
                    $i++;
                }
                return $com;  // вычленненый комментарий 
            }
    }
}

function editComment($f, $com)  // запись комментария к файлу в сам файл - из ДЗ №6
{
    $arr[] = explodeImg($f, 0);  // запоминаем вычленненое изображение, а старый комментарий - нет
    $arr[] = $com;   // добавляем вторым элементом массива новый комментарий
    $newContent = implode('%%%', $arr); // собираем строку файла изображения уже с новым комментарием
    file_put_contents($f, $newContent);  // добавляем эту строку в файл $f
}


?>