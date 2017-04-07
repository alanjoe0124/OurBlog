<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $session = new Session();
    if (!($session->isLogin())) {
        header('Location:/admin/login.php');
    }
    if (!isset($_GET['action'])) {
        throw new InvalidArgumentException("UNDEFINED ACTION");
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("Missing required Blog ID");
    }
    $blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 4294967295)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid Blog ID');
    }
    $blogManage = new BlogManage();
    $blogManage->authority_check($blogId);
    switch ($_GET['action']) {
        case "del":
            $blogManage->delete_blog($blogId);
            header("Location:/admin/blog_manage.php");
            exit;
        case "edit":
            header("Location:/admin/edit_blog.php?blog=$blogId");
            exit;
        default:
            break;
    }
} catch (InvalidArgumentException $e) {
    exit('INVALID PARAM');
} catch (Exception $e) {
    exit("SERVER ERROR");
}
?>