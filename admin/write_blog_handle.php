<?php
require_once("../ClassLib/WriteBlog.class.php");
require_once("../config/config.php");

$indexColumnId=htmlentities(trim($_POST['column']), ENT_COMPAT, 'UTF-8');
$title= htmlentities(trim($_POST['title']),ENT_COMPAT,'GB2312');
$content= htmlentities(trim($_POST['content']),ENT_COMPAT,'GB2312');
$mysqli=new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if(mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
if (!empty($title) && !empty($content) && $indexColumnId!=0)
{
    $writeBlog = new WriteBlog($mysqli);
    $cookieEmail=$writeBlog->user_cookie_check(); 
    $writeBlog->get_user_id($cookieEmail);
    $writeBlog->post_blog($indexColumnId, $title, $content);
}
else
{
    echo "info not complete!";
}
?>