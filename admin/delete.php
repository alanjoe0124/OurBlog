<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    session_start();
    if (!isset($_SESSION['uid'])) {
        header('Location:http://localhost/Ourblog/admin/login.php');
        exit;
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("Missing required Blog ID");
    }
    $blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid Blog ID');
    }
} catch (InvalidArgumentException $e) {
    exit('INVALID PARAM');
}
$data = Mysql::getInstance()->selectRow("SELECT id FROM blog WHERE id = ? AND user_id = ?", array($blogId, $_SESSION['uid']));
if (!$data) {
    exit("sorry, permission denied");
}
Mysql::getInstance()->delete("blog", array('id' => $blogId));
Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId));
header("Location:http://localhost/Ourblog/admin/blog_manage.php");
exit;
