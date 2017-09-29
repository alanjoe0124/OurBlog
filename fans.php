<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
?>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/css/main.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/bootstrap/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" href="/Ourblog/common/bootstrap/font-awesome/css/font-awesome.min.css">
    </head>
    <body>
        <div class="container">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-8 col-md-offset-2">
                            <p><h3><a href="/Ourblog/index.php">OurBlog</a></h3>
                            <?php
                            session_start();
                            if (!isset($_SESSION['uid'])) {
                                echo '&nbsp;<h4><a href="/Ourblog/admin/login.php">login</a></h4>
        |<h4><a href="/Ourblog/admin/register.php">register</a></h4>';
                            } else {
                                echo '&nbsp;&nbsp;<h3><a href="/Ourblog/admin/blog_manage.php">admin</a></h3>';
                            }
                            $columns = array();
                            $columnRows = Mysql::getInstance()->selectAll("SELECT * FROM index_column");
                            foreach ($columnRows as $columnRow) {
                                $columns[$columnRow["id"]] = $columnRow["name"];
                            }
                            foreach ($columns as $key => $value) {
                                echo '&nbsp;&nbsp;&nbsp;<h4><a href="/Ourblog/index.php?col=' . $key . '">' . $value . '</a></h4>';
                            }
                            ?>
                            </p>
                        </div>
                        <?php
                        if (isset($_SESSION['uid'])):
                            ?>
                            <div class="col-md-2">
                                <h4><div id="email"><a href="/Ourblog/user.php?user=<?php echo $_SESSION['uid']; ?>"><?php echo $_SESSION['userEmail']; ?></a></div> | <a href="/Ourblog/admin/logout.php">logout</a></h4>
                            </div>
                            <?php
                        endif;
                        ?>
                    </div>
                </div>

                <div class="row">
                    <?php
                    try {
                        $userId = filter_var($_GET['user'], FILTER_VALIDATE_INT, array(
                            'options' => array('min_range' => 1)
                        ));
                        if (!$userId) {
                            throw new InvalidArgumentException('invalid userId');
                        }
                    } catch (InvalidArgumentException $e) {
                        exit('参数错误');
                    }
                    $userRow = Mysql::getInstance()->selectRow('SELECT email FROM user WHERE id = ?', array($userId));
                    ?>
                    <div class="col-md-8  col-md-offset-2">
                        <div class="col-md-12">
                            <div class="col-md-8">
                                <h2><?php echo $userRow['email']; ?>的粉丝</h2>
                            </div>
                            <?php
                            $redis = new Redis();
                            $conn = $redis->connect('127.0.0.1', 6379);
                            ?>
                        </div>
                        <div class="col-md-12">
                            <?php
                            if (isset($_SESSION['userEmail'])) {
                                if ($_SESSION['userEmail'] != $userRow['email']) {
                                    if (!$redis->sIsMember("blogUser:" . $_SESSION['uid'] . ":following", $userId)) {
                                        echo '<div class="follow"><button class="btn btn-primary following" onclick="following(0)">关注</button></div>';
                                    } else {
                                        echo '<div class="follow"><button class="btn btn-primary cancel-following" onclick="cancel_following(0)">取消关注</button></div>';
                                    }
                                }
                            } else {
                                echo '<div class="follow"><button class="btn btn-primary following" onclick="following(0)">关注</button></div>';
                            }
                            ?>
                        </div>
                        <HR width="100%">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <?php
                            $fans = $redis->sMembers("blogUser:$userId:fans");
                            if (!empty($fans)) {
                                $userIds = implode(',', $fans);
                                $stmt = Mysql::getInstance()->query("SELECT id, email FROM user WHERE id in ($userIds)");

                                foreach ($stmt as $userRow) {
                                    echo '<a href="/Ourblog/user.php?user=' . $userRow['id'] . '">' . $userRow['email'] . '</a><br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
        <script>


            function cancel_following() {
                var postData = {
                    hostUserId: <?php echo $userId; ?>,
                    action: "cancel-following",
                };
                $.post('/Ourblog/follow_handle.php', postData, function (response) {
                    alert(response.res);

                    $(".follow").replaceWith('<div class="follow"><button class="btn btn-primary following" onclick="following(0)">关注</button></div>');
                }, 'json');
            }

            function following() {
                var postData = {
                    hostUserId: <?php echo $userId; ?>,
                    action: "following",
                };
                $.post('/Ourblog/follow_handle.php', postData, function (response) {
                    alert(response.res);
                    if (response.res != 'need login') {
                        $(".follow").replaceWith('<div class="follow"><button class="btn btn-primary cancel-following" onclick="cancel_following(0)">取消关注</button></div>');
                    }
                }, 'json');
            }
        </script>
    </body>
</html>

