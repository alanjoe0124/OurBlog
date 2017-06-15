<?php
if(!isset($_SESSION['uid'])){
    exit("Permission denied");
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/jquery.tagsinput.min.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <script   src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="http://localhost/Ourblog/common/js/jquery.tagsinput.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p>
                    <h3><a href="http://localhost/Ourblog/index.php">OurBlog</a></h3>
                    &nbsp;&nbsp;
                    <h4><a href="http://localhost/Ourblog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="http://localhost/Ourblog/admin/write_blog.php">blog write</a></h4>
                    </p>
                    <h5><?php echo $_SESSION['userEmail']; ?>|<a href="http://localhost/Ourblog/admin/logout.php">logout</a></h5>

                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end-->
            <div class="sidebox"></div>