<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");
$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);
$blogId = htmlentities(trim($_POST['blog']), ENT_COMPAT, 'UTF-8');
$idxColumnId = htmlentities(trim($_POST['column']), ENT_COMPAT, 'UTF-8');
$title = htmlentities(trim($_POST['title']), ENT_COMPAT, 'GB2312');
$content = htmlentities(trim($_POST['content']), ENT_COMPAT, 'GB2312');

if (!empty($title) && !empty($content) )
{
    $editBlog = new EditBlog($blogId, $mysqliExt);
    $session=new Session($mysqliExt);
    $sessionEmail = $session->user_session_check();
    $editBlog->get_user_id($sessionEmail);
    $authorityCheck = $editBlog->authority_check();
    if ($authorityCheck == 1)
    {
        $editBlog->update_blog($idxColumnId,$title,$content);
    }
}else{
     echo "info not complete!";
}


?>