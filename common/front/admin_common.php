<?php 
if(!isset($_SESSION['uid'])){
    exit("Permission denied");
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/common/css/main.css">
        <script   src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p>
                    <h1><a href="/index.php">OurBlog</a></h1>
                    &nbsp;&nbsp;
                    <h4><a href="/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="/admin/write_blog.php">blog write</a></h4>
                    </p>
                    <h4><?php echo $_SESSION['userEmail']; ?>|<a href="/admin/logout.php">logout</a></h4>

                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end-->
  <div class="sidebox"></div>