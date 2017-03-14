<?php
require_once("../config/config.php");
require_once("../ClassLib/AutoLoad.php");
$indexColumnId=htmlentities(trim($_POST['column']), ENT_COMPAT, 'UTF-8');
$title= htmlentities(trim($_POST['title']),ENT_COMPAT,'GB2312');
$content= htmlentities(trim($_POST['content']),ENT_COMPAT,'GB2312');
$mysqliExt = new MysqliExt($host, $dbUser, $dbPwd, $db);
if (!empty($title) && !empty($content) && $indexColumnId!=0)
{
    $writeBlog = new WriteBlog($mysqliExt);
    $session=new Session($mysqliExt);
    $sessionEmail = $session->user_session_check();
    $writeBlog->get_user_id($sessionEmail);
    $writeBlog->post_blog($indexColumnId, $title, $content);
}
else
{
    echo "info not complete!";
}
?>