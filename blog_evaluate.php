<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
session_start();
$redis = new redis();
$conn = $redis->connect('127.0.0.1', 6379);
try {
    if (!isset($_SESSION['uid'])) {
        throw  new InvalidArgumentException('need login!');
    }
} catch (InvalidArgumentException $ex) {
    exit('need login');
}
$userId = $_SESSION['uid'];
if ($_POST) {
    try {
        $blogId = filter_var($_POST['blogId'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
        if (!$blogId) {
            throw  new InvalidArgumentException('param error');
        }
        $blogUserId = filter_var($_POST['blogUserId'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
        if (!$blogUserId) {
            throw  new InvalidArgumentException('param error');
        }
    } catch (InvalidArgumentException $e) {
        echo json_encode(array('res' => '参数错误'));
        exit;
    }
    if ($redis->sIsMember("blogUser:$userId:blogLike", $blogId)) {
        echo json_encode(array('res' => '您已经赞过了'));
        exit;
    }
    if ($redis->sIsMember("blogUser:$userId:blogDislike", $blogId)) {
        echo json_encode(array('res' => '您已经踩过了'));
        exit;
    }
    if ($_POST['evaluate'] == 'like') {
        $blogRow = Mysql::getInstance()->selectRow("SELECT idx_column_id, title FROM blog WHERE id = ?", array($blogId));
        $redis->incr("blog:" . $_POST['blogId'] . ":likeNum");
        $redis->sAdd("blogUser:$userId:blogLike", $_POST['blogId']);
        echo json_encode(array('res' => '谢谢您的赞'));
        // 添加到用户个人文章的好评榜中
        $blogScoreInPersonal = $redis->zScore("blogUser:$blogUserId:blogRank", $blogId.":".$blogRow['title']);
        if ($blogScoreInPersonal === false) {
            $redis->zAdd("blogUser:$blogUserId:blogRank", 1, $blogId.":".$blogRow['title']);
        } else {
            $redis->zIncrBy("blogUser:$blogUserId:blogRank", 1, $blogId.":".$blogRow['title']);
        }
        // 添加到分类文章的好评榜中
        $blogScoreInCategory = $redis->zScore("blogCategory:" . $blogRow['idx_column_id'] . ":blogRank", $blogId.":".$blogRow['title']);
        if ($blogScoreInCategory === false) {
            $redis->zAdd("blogCategory:" . $blogRow['idx_column_id'] . ":blogRank", 1, $blogId.":".$blogRow['title']);
        } else {
            $redis->zIncrBy("blogCategory:" . $blogRow['idx_column_id'] . ":blogRank", 1, $blogId.":".$blogRow['title']);
        }
    } else {
        $redis->incr("blog:" . $blogId . ":dislikeNum");
        $redis->sAdd("blogUser:$userId:blogDislike", $blogId);
        echo json_encode(array('res' => '我做错了什么'));
    }
}
