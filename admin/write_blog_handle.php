<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $classInclude = true;
    require_once __DIR__ . '/../common/admin/validate_blog.php';
} catch (InvalidArgumentException $e) {
    // exit($e->getMessage());
    exit("Param ERROR");
}
$writeBlog = new WriteBlog();
$paramArr = array(
    'idx_column_id' => $columnId,
    'title' => $_POST['title'],
    'user_id' => $_SESSION['uid'],
    'post_time' => date("Y-m-d h:i:s")
);
if ($isValidURL) {
    // post blog with url 
    $paramArr['blog_url'] = $blogURL;
} else {
    // post blog with content
    $paramArr['content'] = $_POST['content'];
}
Mysql::getInstance()->startTrans();
$writeBlog->post_blog($paramArr);
try {
// get tag name's related id
    $newTagIds = array();
    $tagArr = $writeBlog->get_tag_id($tagNames);
    $writeBlog->insert_blog_tag($tagArr['existTagIds']);
    foreach ($tagArr['newTagNames'] as $newTagName) {
        Mysql::getInstance()->insert('tag', array('tag_name' => $newTagName));
        $newTagIds[] = Mysql::getInstance()->getLastInsertId();
    }
    $writeBlog->insert_blog_tag($newTagIds);
    Mysql::getInstance()->commit();
} catch (Exception $e) {
    Mysql::getInstance()->rollback();
    exit('SERVER ERROR');
}
header("Location:/admin/write_blog.php");
?>