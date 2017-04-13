<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    require_once __DIR__ . '/../common/admin/validate_blog.php';
} catch (InvalidArgumentException $e) {
    // exit($e->getMessage());
    exit("Param ERROR");
}
// post blog with url
if ($isInvalidURL) {
    $writeBlog = new WriteBlog();
    $writeBlog->post_blog(array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'blog_url' => $blogURL,
        'user_id' => $_SESSION['uid'],
        'post_time' => date("Y-m-d h:i:s")
    ));
} else {
    // post blog with content
    try {
        if (strlen($_POST['content']) > 64000) {
            throw new InvalidArgumentException('Content maxLength 64000');
        }
    } catch (InvalidArgumentException $e) {
        exit("Param ERROR");
    }
    $writeBlog = new WriteBlog();
    $writeBlog->post_blog(array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'user_id' => $_SESSION['uid'],
        'post_time' => date("Y-m-d h:i:s")
    ));
}
// check tag and filter it, add invalid or nothing to do

$paramTag = array("custom_tag", "recommend_tag", "usual_tag");
$tagNameArr = $writeBlog->validate_tag($paramTag);


if (count($tagNameArr) > 5) {
    exit("Tags' amount should be less than 5");
}
try {
    Mysql::getInstance()->startTrans();
// get tag name's related id
    $tagIdArr = array();
    foreach ($tagNameArr as $value) {
        $res = Mysql::getInstance()->selectRow("SELECT * FROM tag WHERE tag_name = ?", array($value));
        if ($res) {
            if (!in_array($res['id'], $tagIdArr)) {
                $tagIdArr[] = $res['id'];
            }
        } else {
            Mysql::getInstance()->insert('tag', array('tag_name' => $value));
            $tagIdArr[] = Mysql::getInstance()->getLastInsertId();
        }
    }

    $writeBlog->insert_blog_tag($tagIdArr);
// update the usual tag
    $writeBlog->update_usual_tag(new SplQueue(), $tagIdArr);
    Mysql::getInstance()->commit();
} catch (Exception $e) {
    Mysql::getInstance()->rollback();
    exit('SERVER ERROR');
}
header("Location:/admin/write_blog.php");
exit;
?>