<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';
require_once __DIR__ . '/../common/function.php';
try {
    $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }
    //check if url exists. 
    if (isset($_POST['blog_url']) && trim($_POST['blog_url']) != '') {
        $blogURL = filter_var($_POST['blog_url'], FILTER_VALIDATE_URL);
        if (!$blogURL) {
            $flag = false;
            $paramArr = array(
                "column" => $_POST['column'], 
                "title" => $_POST['title'], 
                "content" => $_POST['content']);
        } else {
            $flag = true;
            $paramArr = array(
                "column" => $_POST['column'], 
                "title" => $_POST['title']);
        }
    } else {
        $paramArr = array(
            "column" => $_POST['column'], 
            "title" => $_POST['title'], 
            "content" => $_POST['content']);
    }
    foreach ($paramArr as $key => $val) {
        if (!isset($val)) {
            throw new InvalidArgumentException("Missing required $key");
        }
    }

    $columnId = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 255)
    ));
    if (!$columnId) {
        throw new InvalidArgumentException("Invalid column");
    }
    if (charNum(trim($_POST['title'])) > 100 || charNum(trim($_POST['title'])) < 1) {
        throw new InvalidArgumentException('Title maxLength 100 , minLength 1');
    }
    // post blog with url
    if ($flag) {
        $writeBlog = new WriteBlog();
        $writeBlog->post_blog(array(
            'idx_column_id' => $columnId,
            'title' => trim($_POST['title']),
            'blog_url' => $blogURL,
            'user_id' => $_SESSION['uid'],
            'post_time' => date("Y-m-d h:i:s")
        ));
        header("Location:/admin/write_blog.php");
        exit;
    } else {
        // post blog with content
        if (charNum($_POST['content']) > 65535 || charNum($_POST['content']) < 5) {
            throw new InvalidArgumentException('URL invalid, and content maxLength 65535, minLength 5');
        }
        $writeBlog = new WriteBlog();
        Mysql::getInstance()->startTrans();
        $writeBlog->post_blog(array(
            'idx_column_id' => $columnId,
            'title' => trim($_POST['title']),
            'content' => $_POST['content'],
            'user_id' => $_SESSION['uid'],
            'post_time' => date("Y-m-d h:i:s")
        ));
        // check tag select or not. if so ,add them, or  not actions
        $countCustomTags = 0;
        $countRecommendTags = 0;
        if (isset($_POST['custom_tags']) && trim($_POST['custom_tags']) != '') {
            $arrCustomTags = explode(" ", trim($_POST['custom_tags']));
            $countCustomTags = count($arrCustomTags);
        }

        if (isset($_POST['recommend_tag'])) {
            $countRecommendTags = count($_POST['recommend_tag']);
        }
        if(($countCustomTags + $countRecommendTags) > 5){
            throw new InvalidArgumentException("Tags' amount should be less than 5");
        }else if (($countCustomTags + $countRecommendTags) > 0) {
            if (isset($_POST['recommend_tag'])) {
                foreach ($_POST['recommend_tag'] as $val) {
                    $tagId = filter_var($val, FILTER_VALIDATE_INT, array(
                        'options' => array('min_range' => 1, 'max_range' => 4294967295)
                    ));
                    if (!$tagId) {
                        throw new InvalidArgumentException('Invalid tag id');
                    } else {
                        $writeBlog->add_recommend_tag($val);
                    }
                }
            }
            if (isset($arrCustomTags)) {
                foreach ($arrCustomTags as $vl) {
                    if (charNum($vl) > 20) {
                        throw new InvalidArgumentException("Each of tag's length should be less than 20");
                    } else {
                        $writeBlog->add_custom_tag($vl);
                    }
                }
            }
        }else {}
        Mysql::getInstance()->commit();
        header("Location:/admin/write_blog.php");
        exit;
    }
} catch (InvalidArgumentException $e) {
    exit($e->getMessage());
    exit("Param ERROR");
} catch (Exception $e) {
    Mysql::getInstance()->rollBack();
    exit("SEVER ERROR");
}
?>