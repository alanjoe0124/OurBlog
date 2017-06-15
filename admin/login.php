<?php
session_start();
if (isset($_SESSION['uid'])) {
    header("Location:http://localhost/Ourblog/admin/blog_manage.php");
    exit;
}

if ($_POST) {
    require_once __DIR__ . '/../ClassLib/AutoLoad.php';
    try {
        if (!isset($_POST['email'])) {
            throw new InvalidArgumentException("Missing required email");
        }
        if (!isset($_POST['pwd'])) {
            throw new InvalidArgumentException("Missing required password");
        }
        $emailLength = strlen($_POST['email']);
        if ($emailLength < 3 || $emailLength > 100) {
            throw new InvalidArgumentException("email minlength 3, maxlength 100");
        }
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        if (!$email) {
            throw new InvalidArgumentException("invalid email");
        }
        $pwdLength = strlen($_POST['pwd']);
        if ($pwdLength < 4 || $pwdLength > 50) {
            throw new InvalidArgumentException("password minlength 4, maxlenght 50");
        }
    } catch (InvalidArgumentException $e) {
        exit($e->getMessage());
        exit("INVALID_PARAMS");
    }
    $data = Mysql::getInstance()->selectRow("SELECT id FROM user WHERE email = ? AND pwd = ?", array(
        $email, md5($_POST['pwd'] . 'secret')));
    if ($data) {
        session_start();
        session_regenerate_id();
        $_SESSION['userEmail'] = $email;
        $_SESSION['uid'] = $data['id'];
        header("Location:http://localhost/Ourblog/admin/blog_manage.php");
        exit;
    }
    header("Location:http://localhost/Ourblog/admin/login.php?error=password_failed");
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="http://localhost/Ourblog/common/bootstrap/css/bootstrap.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="headbox">
                    <div class="head-side-box"></div>

                    <div class="head-main-box">
                        <p><h3><a href="http://localhost/Ourblog/index.php">OurBlog</a>/Login</h3>
                        <HR width="100%">
                    </div>
                    <div class="head-side-box"></div>
                </div>
                <div class="sidebox"></div>

                <div class="mainbox">
                    <?php
                    if (isset($_GET['error'])) {
                        if ($_GET['error'] == 'password_failed') {
                            echo '<p style="color:red">密码不对</p>';
                        }
                    }
                    ?>
                    <form  method="post" action="login.php">
                        <div class="row-title">
                            Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="email"  id="email" name="email"  value="">
                        </div>
                        <div class="row-title">
                            password:<input type="password"  id="pwd" name="pwd"  value="">
                        </div>
                        <div class="row-title">
                            <button type="submit" class="btn btn-default">Submit</button>    
                        </div>   
                    </form>
                </div>

            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

