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
                                <h4><div id="email"><a href="/Ourblog/user.php?user=<?php echo $_SESSION['uid'];?>"><?php echo $_SESSION['userEmail']; ?></a></div> | <a href="/Ourblog/admin/logout.php">logout</a></h4>
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
                        <h2><?php echo $userRow['email']; ?>的动态</h2>
                        <HR width="100%">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <?php echo $userRow['email']; ?>的好评文章<br><br>
                        <?php
                        $redis = new Redis();
                        $conn = $redis->connect('127.0.0.1', 6379);
                        $userBlogRank = $redis->zRevRange("blogUser:$userId:blogRank", 0, 10, true);
                        if ($userBlogRank) {
                            foreach ($userBlogRank as $blogInfo => $like) {
                                $blogInfoArr = explode(':', $blogInfo);
                                echo "<div><div class=\"title rankTitle\"><a href=\"/Ourblog/blog_detail.php?blog=" . $blogInfoArr[0] . "\">" . $blogInfoArr[1] . "</a></div><div class=\"likeNum\">" . $like . " 赞</div></div><br><br>";
                            }
                        }
                        ?>
                    </div>
                    <div class="col-md-8">
                        <div class="col-md-12">
                            <?php
                            $timeLine = $redis->lRange("blogUser:$userId:timeLine", 0, -1);
                            echo "<h3>时间线</h3><br>================================================<br><br>";
                            foreach ($timeLine as $yearMonth) {
                                echo "<h4>$yearMonth</h4><br><br><br>";
                                $blogs = $redis->lRange("blogUser:$userId:yearMonth:$yearMonth:blogs", 0, -1);
                                $blogIds = implode(', ', $blogs);
                                $blogRows = Mysql::getInstance()->selectAll("SELECT  id,title,post_time FROM blog where Id in ($blogIds)");
                                foreach ($blogRows as $blogRow) {
                                    $likeNum = $redis->get("blog:" . $blogRow['id'] . ":likeNum");
                                    $likeNum = $likeNum ? $likeNum : 0;
                                    $dislikeNum = $redis->get("blog:" . $blogRow['id'] . ":dislikeNum");
                                    $dislikeNum = $dislikeNum ? $dislikeNum : 0;
                                    echo '<div class="col-md-12 list" onclick="window.location.href=\'/Ourblog/blog_detail.php?blog=' . $blogRow['id'] . '\'">
                                       <div class="col-md-5 title"><a href="javascript:void(0)">' . htmlspecialchars($blogRow['title']) . '</a></div><div class="col-md-4">' .
                                    '赞:' . $likeNum . '/踩:' . $dislikeNum . '</div><div class="col-md-3">' . $blogRow['post_time'] . '</div></div><br><br><br>';
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
    </body>
</html>

