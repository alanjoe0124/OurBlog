
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
                    <p><h1><a href="http://<?php echo $_SERVER['SERVER_NAME']; ?>/OurBlog/index.php">OurBlog</a>/Login</h1>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <form  method="post" action="login_handle.php">

                    <div class="row-title">
                        Email:<input type="text"  id="email" name="email"  value="">

                    </div>
                    <div class="row-title">
                        password:<input type="password"  id="pwd" name="pwd"  value="">
                    </div>
                    <div class="row-title">
                        <input type="submit" name='submit' value="submit">
                    </div>   
                </form>
            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

