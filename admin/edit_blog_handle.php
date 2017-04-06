<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $session = new Session();
    if (!($session->isLogin())) {
        header('Location:/admin/login.php');
    }
    if (!isset($_POST['blog'])) {
        throw New InvalidArgumentException('Undefined blog');
    }
    if (!isset($_POST['column'])) {
        throw New InvalidArgumentException('Undefined Column');
    }
    if (!isset($_POST['title'])) {
        throw New InvalidArgumentException('Undefined Title');
    }
    if (!isset($_POST['content'])) {
        throw New InvalidArgumentException('Undefined Content');
    }
    if (strlen($_POST['title']) > 100) {
        throw New InvalidArgumentException('Title maxLength 100');
    }
    if (strlen($_POST['content']) > 16777215) {
        throw New InvalidArgumentException('Content maxLength 16777215');
    }
    $blogId = filter_var($_POST['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 4294967295)));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
    $idx_column_id = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 255)));
    if (!$idx_column_id) {
        throw new InvalidArgumentException("invalid column");
    }
    $editBlog = new EditBlog();
    Mysql::getInstance()->startTrans();
    $editBlog->authority_check($blogId);
    $editBlog->update_blog(
            array(
        'idx_column_id' => $idx_column_id,
        'title' => $_POST['title'],
        'content' => $_POST['content'],
        'post_time' => date("Y-m-d H:i:s")
            ), array('id' => $blogId)
    );
    $countCustomTags = 0;
    $countSysTags = 0;
    if (isset($_POST['custom_tags']) && trim($_POST['custom_tags']) != '') {
        $arrCustomTags = explode(" ", trim($_POST['custom_tags']));
        $countCustomTags = count($arrCustomTags);
    }

    if (isset($_POST['sys_tag'])) {
        $countSysTags = count($_POST['sys_tag']);
    }
    if (($countCustomTags + $countSysTags) > 5) {
        throw New InvalidArgumentException("Tags' amount should be less than 5");
    } else if (($countCustomTags + $countSysTags) != 0) {
        $editBlog->delete_blog_all_tag();
        if (isset($_POST['sys_tag'])) {
            foreach ($_POST['sys_tag'] as $val) {
                $tagId = filter_var($val, FILTER_VALIDATE_INT, array(
                    'options' => array('min_range' => 1, 'max_range' => 4294967295)
                ));
                if (!$tagId) {
                    throw new InvalidArgumentException('Invalid tag id');
                } else {
                    $editBlog->add_sys_tag($val);
                }
            }
        }
        if (isset($arrCustomTags)) {
            foreach ($arrCustomTags as $vl) {
                if (strlen($vl) > 20) {
                    throw New InvalidArgumentException("Each of defined tag's length should be less than 20");
                } else {
                    $editBlog->add_custom_tag($vl);
                }
            }
        }
    } else {
        $editBlog->delete_blog_all_tag();
    }
    Mysql::getInstance()->commit();
    header("Location:/admin/blog_manage.php");
} catch (InvalidArgumentException $e) {
    //echo $e->getMessage();
    exit("INVALID PARAM");
} catch (Exception $e) {
    //echo $e->getMessage();
    Mysql::getInstance()->rollBack();
    exit("SERVER ERROR");
}
?>