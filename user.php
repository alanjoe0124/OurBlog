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
                <?php
                $permission = true;
                require_once __DIR__ . '/common/front/index_common.php';
                ?> 
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        <div class="col-md-12">
                            <?php
                            $redis = new Redis();
                            $conn = $redis->connect('127.0.0.1', 6379);
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
                            $timeLine = $redis->lRange("blogUser:$userId:timeLine", 0, -1);
                            foreach ($timeLine as $yearMonth) {
                                echo "<h3>$yearMonth</h3>";
                                $blogs = $redis->lRange("blogUser:$userId:yearMonth:$yearMonth:blogs", 0, -1);
                                $blogIds = implode(', ', $blogs);
                                $blogRows = Mysql::getInstance()->selectAll("SELECT  id,title,post_time FROM blog where Id in ($blogIds)");
                                foreach ($blogRows as $blogRow) {
                                    echo '<div class="col-md-12 list" onclick="window.location.href=\'/Ourblog/blog_detail.php?blog=' . $blogRow['id'] . '\'">
                                       <div class="col-md-5"><a href="javascript:void(0)">' . htmlspecialchars($blogRow['title']) . '</a></div><div class="col-md-4">' .
                                    '赞:0/踩:0' .
                                    '</div><div class="col-md-3">' .
                                    $blogRow['post_time'] .
                                    '</div></div><br><br><br>';
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="http://localhost/Ourblog/common/js/jquery-3.2.1.min.js"></script>
        <script src="http://localhost/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
    </body>
</html>

