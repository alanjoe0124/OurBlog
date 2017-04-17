<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $classInclude = true;
    require_once __DIR__ . '/../common/admin/validate_blog.php';

    $blogId = filter_var($_POST['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
} catch (InvalidArgumentException $e) {
    // exit($e->getMessage());
    exit("Param ERROR");
}

// update blog with url
Mysql::getInstance()->startTrans();
if ($isInvalidURL) {
    $editBlog = new EditBlog();
    $editBlog->update_blog(array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'blog_url' => $blogURL,
        'content' => NULL,
        'user_id' => $_SESSION['uid'],
        'post_time' => date("Y-m-d h:i:s")
            ), array('id' => $blogId));
} else {
    // update blog with content
    try {
        if (strlen($_POST['content']) > 64000) {
            throw new InvalidArgumentException('Content maxLength 64000');
        }
    } catch (InvalidArgumentException $e) {
        exit("Param ERROR");
    }

    $editBlog = new EditBlog();
    $editBlog->update_blog(array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'blog_url' => NULL,
        'content' => $_POST['content'],
        'user_id' => $_SESSION['uid'],
        'post_time' => date("Y-m-d h:i:s")
            ), array('id' => $blogId));
}

// check tag and filter it, add invalid or nothing to do


$paramTag = array("custom_tag", "recommend_tag", "latest_tag", "current_tag");
$submitTagNameArr = $editBlog->validate_tag($paramTag);

if (count($submitTagNameArr) > 5) {
    exit("Tags' amount should be less than 5");
}

try {
// get tag name's related id
    $tagIdArr = $editBlog->get_submit_tag_id($submitTagNameArr);
    if (!empty($tagIdArr)) {
        $editBlog->update_blog_tag($tagIdArr, $blogId);
    } else {
        Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId));
    }
    Mysql::getInstance()->commit();
} catch (Exception $e) {
    Mysql::getInstance()->rollback();
    exit($e->getMessage());
    exit('SERVER ERROR');
}
header("Location:/admin/blog_manage.php");
exit;
?>