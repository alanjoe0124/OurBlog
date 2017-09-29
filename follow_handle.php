<?php

require_once __DIR__ . '/ClassLib/AutoLoad.php';
session_start();
$redis = new redis();
$conn = $redis->connect('127.0.0.1', 6379);
try {
    if (!isset($_SESSION['uid'])) {
        throw new InvalidArgumentException('need login!');
    }
} catch (InvalidArgumentException $ex) {
    exit(json_encode(array('res'=>'need login')));
}
$userId = $_SESSION['uid'];
if ($_POST) {
    try {
        $hostUserId = filter_var($_POST['hostUserId'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
        if (!$hostUserId) {
            throw new InvalidArgumentException('param error');
        }
    } catch (InvalidArgumentException $e) {
        echo json_encode(array('res' => '参数错误'));
        exit;
    }
    if ($_POST['action'] == 'following') {
        if ($redis->sIsMember("blogUser:$userId:following", $hostUserId)) {
            echo json_encode(array('res' => '您已经关注过了'));
            exit;
        }
        $redis->sAdd("blogUser:$userId:following", $hostUserId);
        $redis->sAdd("blogUser:$hostUserId:fans", $userId);
        $redis->incr("blogUser:$hostUserId:fansNum");
        $redis->incr("blogUser:$userId:followingNum");
        echo json_encode(array('res' => '关注成功'));
    } else {
        if (!$redis->sIsMember("blogUser:$userId:following", $hostUserId)) {
            echo json_encode(array('res' => '您还未关注过'));
            exit;
        }
        $redis->srem("blogUser:$userId:following", $hostUserId);
        $redis->srem("blogUser:$hostUserId:fans", $userId);
        $redis->decr("blogUser:$hostUserId:fansNum");
        $redis->decr("blogUser:$userId:followingNum");
        echo json_encode(array('res' => '已取消关注'));
    }
}

