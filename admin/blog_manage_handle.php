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
    $action = array('logout', 'edit', 'del');
    if (!in_array($_GET['action'], $action)) {
        throw new InvalidArgumentException('ACTION FAILED');
    }
    if ($_GET['action'] == "logout") {
        $blogManage = new BlogManage();
        $blogManage->logout();
        header("Location:/index.php");
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("Blog not select");
    }
    $blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 4294967295)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
    $blogManage = new BlogManage();
    $blogManage->action_judge($_GET['action'], $blogId);
} catch (InvalidArgumentException $e) {
    exit('INVALID PARAM');
} catch (Exception $e) {
    exit("SERVER ERROR");
}
?>