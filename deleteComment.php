<?php

require_once __DIR__ . "/ClassLib/AutoLoad.php";
try {
    session_start();
    if ( empty($_SESSION) || !isset($_SESSION) ) {
        exit ('login please!');
    }
    if (!isset($_GET['commentId'])) {
        throw new InvalidArgumentException('missing required commentId');
    }
    $commentId = filter_var($_GET['commentId'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$commentId) {
        throw new InvalidArgumentException('invalid comment id');
    }
    $blogId = filter_var($_GET['blogId'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('invalid blog id');
    }
    $res = Mysql::getInstance()->selectRow('SELECT id FROM comment WHERE id = ? and user_id = ?', array($commentId, $_SESSION['uid']));
    if (!$res) {
        exit('permission denied');
    }
    
    $sonCommentRows = Mysql::getInstance()->selectAll('SELECT id FROM comment WHERE parent_id = ?', array($commentId));
    if ( $sonCommentRows ) {
        $sonCommentIds = array();
        foreach ($sonCommentRows as $comment) {
            $sonCommentIds [] = $comment ['id'];
        }
        $commentIds = implode(', ', $sonCommentIds);
        Mysql::getInstance()->exec("UPDATE comment SET parent_id = 0 WHERE id IN ($commentIds)");
    }
    Mysql::getInstance()->delete('comment', array('id' => $commentId));
    header('Location:/Ourblog/blog_detail.php?blog='.$blogId);
} catch (InvalidArgumentException $e) {
    exit('param error');
} catch (Exception $e) {
    exit('server error');
}


