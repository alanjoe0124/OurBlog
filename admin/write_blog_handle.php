<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    $classInclude = true;
    require_once __DIR__ . '/../common/admin/validate_blog.php';
} catch (InvalidArgumentException $e) {
    // exit($e->getMessage());
    exit("Param ERROR");
}
// post blog with url
Mysql::getInstance()->startTrans();
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

$paramTag = array("custom_tag", "recommend_tag", "latest_tag");
$submitTagNameArr = $writeBlog->validate_tag($paramTag);


if (count($submitTagNameArr) > 5) {
    exit("Tags' amount should be less than 5");
}
try {
// get tag name's related id
    $tagIdArr = $writeBlog->get_submit_tag_id($submitTagNameArr);
    $writeBlog->insert_blog_tag($tagIdArr);
    Mysql::getInstance()->commit();
} catch (Exception $e) {
    Mysql::getInstance()->rollback();
    exit('SERVER ERROR');
}
header("Location:/admin/write_blog.php");
exit;
?>