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
$data = Mysql::getInstance()->selectRow("SELECT id, post_time FROM blog WHERE id = ? AND user_id = ?", array($blogId, $_SESSION['uid']));
if (!$data) {
    exit("sorry, permission denied");
}
Mysql::getInstance()->delete("blog", array('id' => $blogId));
Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId));

// remove blog id from user redis list
$redis = new redis();
$conn = $redis->connect('127.0.0.1', 6379);
$postTime = explode('-', $data['post_time']);
$yearMonth = $postTime['0'].'-'.$postTime['1'];

$userId = $_SESSION['uid'];
$redis->lrem("blogUser:$userId:yearMonth:$yearMonth:blogs", $blogId,0);
$yearMonthBlogs = $redis->lRange("blogUser:$userId:yearMonth:$yearMonth:blogs", 0, -1);
if (empty($yearMonthBlogs)) {
    $redis->del("blogUser:$userId:yearMonth:$yearMonth:blogs");
    $redis->lrem("blogUser:$userId:timeLine", $yearMonth, 0);
}
header("Location:http://localhost/Ourblog/admin/blog_manage.php");
exit;
