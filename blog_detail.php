<?php 
require_once __DIR__."/ClassLib/AutoLoad.php";

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
                    <p><h1><a href="/index.php">OurBlog</a></h1>
                    <?php
                       $session = new Session();
                        if (!$session->isLogin()) {
                            echo '&nbsp;<h4><a href="/admin/login.php">login</a></h4>
                                   |<h4><a href="/admin/register.php">register</a></h4>';
                        }else{
                             echo '&nbsp;&nbsp;<h1><a href="/admin/blog_manage.php">admin</a></h1>'; 
                        }
                        $blogDetail = new BlogDetail();
                        $listColumns = $blogDetail->list_idx_columns();
                        foreach($listColumns as $value){
                            echo '&nbsp;<h4><a href="index.php?col='.$value['id'].'">'.$value['name'].'</a></h4>';
                        }
                    ?>
                    </p>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                    $listBlogDetail=$blogDetail->list_blog_detail();
                    foreach($listBlogDetail as $val){
                        echo '<div class="row-title-leftAlign"><h2>'.htmlspecialchars($val['title']).'</h2></div><div class="row-content">'.htmlspecialchars($val['content']).'</div>';
                    }
                ?>
                
            </div>
            <div class="sidebox"></div>
             <!--contetn_body end-->
        </div>
    </body>
</html>

