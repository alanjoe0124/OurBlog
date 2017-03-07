<?php
require_once("../ClassLib/EditBlog.class.php");
require_once("../config/config.php");
$mysqli = new mysqli("$host", "$dbUser", "$dbPwd", "$db");
if (mysqli_connect_errno())
{
    echo mysqli_connect_error();
}
$blogId = htmlentities(trim($_GET['blog']), ENT_COMPAT, 'UTF-8');
$editBlog = new EditBlog($blogId, $mysqli);
$cookieEmail = $editBlog->user_cookie_check();
$editBlog->get_user_id($cookieEmail);
$authorityCheck = $editBlog->authority_check();
if ($authorityCheck == 1)
{
    $idxColumnList = $editBlog->list_idx_columns();
    $blogInfo = $editBlog->list_blog_info();
    foreach ($blogInfo as $val_blog)
    {
        $idxColumnId = $val_blog['idx_column_id'];
        $idxColumnName = $val_blog['name'];
        $title = $val_blog['title'];
        $content = $val_blog['content'];
    }
}
?>
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="../common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p><h1><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/index.php">OurBlog</a>/edit_blog</h1>
                    &nbsp;&nbsp;<h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;<h4><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/admin/write_blog.php">blog write</a></h4></p>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <form  method="post" action="edit_blog_handle.php">

                    <div class="row-title">
                        column:
                        <select name="column">
                            <option value="<?php echo $idxColumnId; ?>" selected="selected"><?php echo $idxColumnName ?></option>
                            <?php
                            foreach ($idxColumnList as $key => $value)
                            {
                                echo "<option value=\"{$value['id']}\" > {$value['name']}</option>";
                            }
                            ?>
                        </select>

                    </div>
                    <div class="row-title">
                        title:<input type="text"  id="title" name="title"  value="<?php echo $title; ?>" >
                    </div>
                    <div class="row-text">
                        text:<textarea name="content" rows = "10"> <?php echo $content; ?></textarea>
                    </div>
                    <div class="row-title">
                        <input type="hidden" name='blog' value="<?php echo $blogId; ?>">
                        <input type="submit" name='submit' value="submit">
                    </div>   
                </form>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

