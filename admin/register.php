<?php
if ($_POST) {
    include __DIR__ . '/../ClassLib/AutoLoad.php';

    try {
        if (!isset($_POST['email'])) {
            throw new InvalidArgumentException("missing required key email");
        }
        if (!isset($_POST['pwd'])) {
            throw new InvalidArgumentException("missing required key pwd");
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
            throw new InvalidArgumentException("pwd minlength 4, maxlength 50");
        }
        $userRow = Mysql::getInstance()->selectRow("select * from user where email = ?", array($email));
        if ($userRow) {
            throw new InvalidArgumentException("email exists");
        }

        Mysql::getInstance()->insert('user', array(
            'email' => $email,
            'pwd' => md5($_POST['pwd'] . 'secret'),
            'reg_time' => date('Y-m-d H:i:s')
        ));
        session_start();
        session_regenerate_id();
        $_SESSION['userEmail'] = $email;
        $_SESSION['uid'] = Mysql::getInstance()->getLastInsertId();
    } catch (InvalidArgumentException $e) {
        exit($e->getMessage());
        //header("Location: http://localhost/Ourblog/admin/register.php?error=INVALID_PARAMS");
        exit;
    } catch (Exception $e) {
        exit('SERVER_ERROR');
    }

    header("Location: http://localhost/Ourblog/admin/blog_manage.php");
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
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p><h3><a href="http://localhost/Ourblog/index.php">OurBlog</a>/register</h3>
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
                <form  method="post" action="register.php">

                    <div class="row-title">
                        Email:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text"  id="email" name="email"  value="">

                    </div>
                    <div class="row-title">
                        password:<input type="password"  id="pwd" name="pwd"  value="">
                    </div>
                    <div class="row-title">
                        <button type="submit" class="btn btn-default">Submit</button> 
                    </div>   
                </form>
            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

