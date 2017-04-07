
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
                    <p><h1><a href="/index.php">OurBlog</a>/register</h1>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'INVALID_PARAMS') {
                            echo '<p style="color:red">参数不对</p>';
                        }
                    }
                ?>
                <form  method="post" action="register_handle.php">

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

