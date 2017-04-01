<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
$session = new Session();
if (!($session->isLogin())) {
    header('Location:/admin/login.php');
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p>
                    <h1><a href="/index.php">OurBlog</a>/blog_manage</h1>
                    &nbsp;&nbsp;
                    <h4><a href="/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="/admin/write_blog.php">blog write</a></h4>
                    </p>
                    <h4><?php echo $_SESSION['userEmail']; ?>|<a href="/admin/blog_manage_handle.php?action=logout">logout</a></h4>

                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                $blogManage = new BlogManage();
                $res = $blogManage->list_user_blog($_SESSION['uid']);
                foreach ($res as $key => $value) {
                    echo '<div class="row-title">
                            <div class="row-manage-title">
                                <a href="/blog_detail.php?blog=' . $value['id'] . '">' . htmlspecialchars($value['title']) . '</a>
                            </div>
                            <div class="row-action-title">
                                <a href="/admin/blog_manage_handle.php?action=edit&blog=' . $value['id'] . '">edit</a>/
                                <a href="/admin/blog_manage_handle.php?action=del&blog=' . $value['id'] . '">delete</a>
                            </div>
                         </div>';
                }
                ?>
            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

