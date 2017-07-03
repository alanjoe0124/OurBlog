<?php
if (!isset($_SESSION['uid'])) {
    exit("Permission denied");
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/jquery.tagsinput.min.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <script   src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="http://localhost/Ourblog/common/js/jquery.tagsinput.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
        <script src="http://localhost/ticket/public/common/js/ticket.js"></script>
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="row">

                <div class="col-md-8 col-md-offset-2">
                    <p>
                    <h3><a href="http://localhost/Ourblog/index.php">OurBlog</a></h3>
                    &nbsp;&nbsp;
                    <h4><a href="http://localhost/Ourblog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="http://localhost/Ourblog/admin/write_blog.php">blog write</a></h4>
                    </p>
                    <h5><div id="email"><?php echo $_SESSION['userEmail']; ?></div> | <a href="http://localhost/Ourblog/admin/logout.php">logout</a></h5>
                    &nbsp;
                    <button type="submit" class="btn btn-default" onclick="createTicket()">create ticket</button>
                    <button type="submit" class="btn btn-default" onclick="viewMyTickets()">view ticket</button>
                    <HR width="100%">
                </div>

            </div>
            <!--content_head end-->