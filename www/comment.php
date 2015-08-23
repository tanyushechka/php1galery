<?php 

require 'functions.php';
session_start();
if ('save' == $_POST['edit']) {
    $_SESSION['comment'] = $_POST['comments'];

    editComment('images' . DIRECTORY_SEPARATOR . $_SESSION['img'], $_SESSION['comment']);
} else {
	echo $_POST['comment'];
    echo $_POST['edit'];
    $_SESSION['comment'] = explodeImg('images' . DIRECTORY_SEPARATOR .'behemoth.jpg', 1);
    $_SESSION['img'] = $_POST['edit'];
    if ('' == $_SESSION['comment']) {
        $_SESSION['comment'] = 'NO_COMMENTS';
    }
}
// header('Location: index.php');

?>