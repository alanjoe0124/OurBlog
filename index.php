<?php
require_once __DIR__ . '/ClassLib/AutoLoad.php';
$permission = true;
require_once __DIR__ . '/common/front/index_common.php';
?> 
<div class="row">
    <div class="row"  style="height: 450px">
        <div class="col-md-2">
            <?php
            if (isset($_GET['col'])) {
                $col = filter_var($_GET['col'], FILTER_VALIDATE_INT, array(
                    'options' => array('min_range' => 1, 'max_range' => 255)));
            } else {
                $col = NULL;
            }
            if ($col) {
                $columnRow = Mysql::getInstance()->selectRow('SELECT name FROM index_column WHERE id = ?', array($col));
                $redis = new Redis();
                $conn = $redis->connect('127.0.0.1', 6379);
                $categoryBlogRank = $redis->zRevRange("blogCategory:" . $col . ":blogRank", 0, 10, true);
                if ($categoryBlogRank) {
                    echo "<h4>" . $columnRow['name'] . "好评文章</h4><br><br>";
                    foreach ($categoryBlogRank as $blogInfo => $like) {
                        $blogInfoArr = explode(':', $blogInfo);
                        echo "<div><div class=\"title rankTitle\"><a href=\"/Ourblog/blog_detail.php?blog=" . $blogInfoArr[0] . "\">" . $blogInfoArr[1] . "</a></div><div class=\"likeNum\">" . $like . " 赞</div></div><br><br>";
                    }
                }
            }
            ?>
        </div>
        <?php
        $page = new Page(10);
        if (!$col) {
            $page->totalPages = ceil(Mysql::getInstance()->count("select count(*) from blog") / $page->listRows);

            $listBlogs = Mysql::getInstance()->selectAll("select blog.id as id, title, post_time, email from blog join user on user.id = blog.user_id order by post_time desc limit " . ($page->offset) . "," . ($page->listRows));
        } else {
            $page->totalPages = ceil(Mysql::getInstance()->count("select count(*) from blog where idx_column_id = " . $col) / $page->listRows);
            $listBlogs = Mysql::getInstance()
                    ->selectAll("select blog.id as id, title, post_time, email from blog join user on user.id = blog.user_id  where idx_column_id = ? order by post_time desc limit " .
                    ($page->offset) . "," . ( $page->listRows), array($col));
        }
        if ($listBlogs) {
            echo '<div class="col-md-8">';
            foreach ($listBlogs as $blogInfo) {
                echo '<div class="col-md-12 list" onclick="window.location.href=\'/Ourblog/blog_detail.php?blog=' . $blogInfo['id'] . '\'">
                                       <div class="col-md-5 title"><a href="javascript:void(0)">' . htmlspecialchars($blogInfo['title']) . '</a></div><div class="col-md-4">' .
                $blogInfo['email'] .
                '</div><div class="col-md-3">' .
                $blogInfo['post_time'] .
                '</div></div><br><br>';
            }
            echo '</div>';
        }
        ?>
    </div>
    <div class="row page">
        <div class="col-md-8 col-md-offset-2">
            <?php echo $page->show(); ?>
        </div>
    </div>
</div>
</div>
<script src="/Ourblog/common/js/jquery-3.2.1.min.js"></script>
<script src="/Ourblog/common/bootstrap/js/bootstrap.min.js"></script>
</body>
</html>

