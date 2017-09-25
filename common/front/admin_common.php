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
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/css/jquery.tagsinput.min.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <script   src="/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script type="text/javascript" src="/Ourblog/common/js/jquery.tagsinput.min.js"></script>
        <script src="/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
        <script src="/ticket/public/common/js/ticket.js"></script>
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="row">

                <div class="col-md-8 col-md-offset-2">
                    <p>
                    <h3><a href="/Ourblog/index.php">OurBlog</a></h3>
                    &nbsp;&nbsp;
                    <h4><a href="/Ourblog/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;
                    <h4><a href="/Ourblog/admin/write_blog.php">blog write</a></h4>
                    </p>

                    &nbsp;
                    <button type="submit" class="btn btn-default" onclick="createTicket()">create ticket</button>
                    <button type="submit" class="btn btn-default" onclick="viewMyTickets()">view ticket</button>
                    <HR width="100%">
                </div>
                <div class="col-md-2">
                    <h4><div id="email"><a href="/Ourblog/user.php?user=<?php echo $_SESSION['uid'];?>"><?php echo $_SESSION['userEmail']; ?></a></div> | <a href="/Ourblog/admin/logout.php">logout</a></h4>
                </div>
            </div>
            <!--content_head end-->