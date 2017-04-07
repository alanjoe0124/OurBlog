<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
require_once __DIR__ . '/../common/function.php';
try {
    $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }
    $paramArr = array('blog'=>$_POST['blog'], 'column'=>$_POST['column'], 'title'=>$_POST['title'], 'content'=>$_POST['content']);
    foreach($paramArr as $key=>$val){
        if(!isset($val)){
            throw new InvalidArgumentException('Missing required $key');
        }
    }
    if (charNum($_POST['title']) > 100) {
        throw new InvalidArgumentException('Title maxLength 100');
    }
    if (charNum($_POST['content']) > 65535) {
        throw new InvalidArgumentException('Content maxLength 65535');
    }
    $blogId = filter_var($_POST['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 4294967295)));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
    $columnId = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 255)));
    if (!$columnId) {
        throw new InvalidArgumentException("Invalid column");
    }
    $editBlog = new EditBlog();
    Mysql::getInstance()->startTrans();
    $editBlog->authority_check($blogId);
    $editBlog->update_blog(
            array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'post_time' => date("Y-m-d H:i:s")
            ), array('id' => $blogId)
    );
    $countCustomTags = 0;
    $countRecommendTags = 0;
    if (isset($_POST['custom_tags']) && trim($_POST['custom_tags']) != '') {
        $arrCustomTags = explode(" ", trim($_POST['custom_tags']));
        $countCustomTags = count($arrCustomTags);
    }

    if (isset($_POST['recommend_tag'])) {
        $countRecommendTags = count($_POST['recommend_tag']);
    }
    if (($countCustomTags + $countRecommendTags) > 5) {
        throw new InvalidArgumentException("Tags' amount should be less than 5");
    } else if (($countCustomTags + $countRecommendTags) != 0) {
        $editBlog->delete_blog_all_tag($blogId);
        if (isset($_POST['recommend_tag'])) {
            foreach ($_POST['recommend_tag'] as $val) {
                $tagId = filter_var($val, FILTER_VALIDATE_INT, array(
                    'options' => array('min_range' => 1, 'max_range' => 4294967295)
                ));
                if (!$tagId) {
                    throw new InvalidArgumentException('Invalid tag id');
                } else {
                    $editBlog->add_recommend_tag($val,$blogId);
                }
            }
        }
        if (isset($arrCustomTags)) {
            foreach ($arrCustomTags as $vl) {
                if (charNum($vl) > 20) {
                    throw new InvalidArgumentException("Each of defined tag's length should be less than 20");
                } else {
                    $editBlog->add_custom_tag($vl,$blogId);
                }
            }
        }
    } else {
        $editBlog->delete_blog_all_tag($blogId);
    }
    Mysql::getInstance()->commit();
    header("Location:/admin/blog_manage.php");
    exit;
} catch (InvalidArgumentException $e) {
    //echo $e->getMessage();
    exit($e->getMessage());
    exit("INVALID PARAM");
} catch (Exception $e) {
    //echo $e->getMessage();
    Mysql::getInstance()->rollBack();
    exit("SERVER ERROR");
}
?>