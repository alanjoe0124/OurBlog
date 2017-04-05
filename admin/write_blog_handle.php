<?php

require_once __DIR__ . '/../ClassLib/AutoLoad.php';

try {
    $session = new Session();
    if (!($session->isLogin())) {
        header('Location:/admin/login.php');
    }
    if (!isset($_POST['column'])) {
        throw new InvalidArgumentException("undefined column");
    }
    if (!isset($_POST['title'])) {
        throw new InvalidArgumentException('undefined title');
    }
    if (!isset($_POST['content'])) {
        throw new InvalidArgumentException('undefined content');
    }
    $idx_column_id = filter_var($_POST['column'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 255)));
    if (!$idx_column_id) {
        throw new InvalidArgumentException("invalid column");
    }
    if (strlen($_POST['title']) > 100) {
        throw New InvalidArgumentException('Title maxLength 100');
    }
    if (strlen($_POST['content']) > 16777215) {
        throw New InvalidArgumentException('Content maxLength 16777215');
    }
    $writeBlog = new WriteBlog();
    Mysql::getInstance()->startTrans();
    $writeBlog->post_blog();
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
        if (isset($_POST['sys_tag'])) {
            foreach ($_POST['sys_tag'] as $val) {
                $tagId = filter_var($val, FILTER_VALIDATE_INT, array(
                    'options' => array('min_range' => 1, 'max_range' => 4294967295)
                ));
                if (!$tagId) {
                    throw new InvalidArgumentException('Invalid tag id');
                } else {
                    $writeBlog->add_sys_tag($val);
                }
            }
        }
        if (isset($arrCustomTags)) {
            foreach ($arrCustomTags as $vl) {
                if (strlen($vl) > 20) {
                    throw New InvalidArgumentException("Each of defined tag's length should be less than 20");
                } else {
                    $writeBlog->add_custom_tag($vl);
                }
            }
        }
    } else {
        
    }
    Mysql::getInstance()->commit();
    header("Location:/admin/write_blog.php");
} catch (InvalidArgumentException $e) {
    exit("Param ERROR");
} catch (Exception $e) {
    Mysql::getInstance()->rollBack();
    exit("SEVER ERROR");
}
?>