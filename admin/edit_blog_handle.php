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
if ($isInvalidURL) {
    $editBlog = new EditBlog();
    $editBlog->update_blog(array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'blog_url' => $blogURL,
        'content' => NULL,
        'user_id' => $_SESSION['uid'],
        'post_time' => date("Y-m-d h:i:s")
    ),array('id'=>$blogId));
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
    ),array('id'=>$blogId));
}

// check tag and filter it, add invalid or nothing to do


$paramTag = array("custom_tag", "recommend_tag", "usual_tag", "current_tag");
$tagNameArr = $editBlog->validate_tag($paramTag);

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
 if(!empty($tagIdArr)){
    $editBlog->update_blog_tag(new SplQueue(), $tagIdArr, $blogId);
    $editBlog->update_usual_tag(new SplQueue(), $tagIdArr);   
 }else{
    Mysql::getInstance()->delete("blog_tag", array('blog_id' => $blogId));
 }
    
    Mysql::getInstance()->commit();
} catch (Exception $e) {
    Mysql::getInstance()->rollback();
    exit('SERVER ERROR');
}
header("Location:/admin/blog_manage.php");
exit;
?>