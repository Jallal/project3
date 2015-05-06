<?php
/**
 * Created by PhpStorm.
 * User: elhazzat
 * Date: 2/20/15
 * Time: 9:01 PM
 */
session_start();
$tmpName = trim($_POST['name']);
if($_POST['name'] === null || empty($tmpName)) {
    header('Location: index.php');
    exit;
}
$_SESSION['name'] = $_POST['name'];

header('Location: game.php');


?>