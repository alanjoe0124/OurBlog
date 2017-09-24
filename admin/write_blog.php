<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
require_once __DIR__ . '/../common/admin/check_login.php';
if ($_POST) {
    try {
        $permission = true;
        require_once __DIR__ . '/../common/admin/validate_blog.php';
    } catch (InvalidArgumentException $e) {
        exit($e->getMessage());
        //exit("Param ERROR");
    }
    $paramArr = array(
        'idx_column_id' => $columnId,
        'title' => $_POST['title'],
        'user_id' => $_SESSION['uid'],
        'content' => $_POST['content'],
        'post_time' => date("Y-m-d H:i:s")
    );

    Mysql::getInstance()->startTrans();
    try {
        Mysql::getInstance()->insert('blog', $paramArr);
        $blogId = Mysql::getInstance()->getLastInsertId();
        if (isset($_POST['tags'])) {
            if (trim($_POST['tags']) != '') {
                $tags = explode(',', $_POST['tags']);
                foreach ($tags as $tag) {
                    $tagRow = Mysql::getInstance()->selectRow("select id from tag where tag_name = ?", array($tag));
                    if (!$tagRow) {
                        Mysql::getInstance()->insert('tag', array('tag_name' => $tag));
                        $tagId = Mysql::getInstance()->getLastInsertId();
                        Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $tagId));
                    } else {
                        Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $tagRow['id']));
                    }
                }
            }
        }
        // push blog id into user redis list
        $redis = new redis();
        $conn = $redis->connect('127.0.0.1', 6379);
        $yearMonth = date('Y-m');
        $userId = $_SESSION['uid'];
        
        // 判断timeLine是否有该年月
        $timeLine = $redis->lRange("blogUser:$userId:timeLine", 0, -1);
        if (!in_array($yearMonth, $timeLine)) {
            $redis->rPush("blogUser:$userId:timeLine", $yearMonth);
        }
        //存入该年月的list中
        $redis->rPush("blogUser:$userId:yearMonth:$yearMonth:blogs",$blogId);
    } catch (Exception $e) {
        Mysql::getInstance()->rollback();
    }
    Mysql::getInstance()->commit();
    header("Location:http://localhost/Ourblog/admin/write_blog.php");
    exit;
}
require_once __DIR__ . '/../common/front/admin_common.php';
?>


<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <form  method="post" action="write_blog.php">
            <div class="form-group row">
                <div class="col-md-4 control-label"> column: </div>
                <div class="col-md-4">
                    <select class="form-control" name="column">
                        <option value="0" selected="selected">select one please</option>
                        <?php
                        $columnRows = Mysql::getInstance()->selectAll("select * from index_column");
                        foreach ($columnRows as $row) {
                            $columns[$row['id']] = $row['name'];
                        }
                        foreach ($columns as $columnId => $columnName) {
                            echo '<option value=' . $columnId . '>' . $columnName . '</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">title:</div>
                <div class="col-md-4"><input class="form-control" type="text"  id="title" name="title"  value="" placeholder="title..."></div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">text:</div>
                <div class="col-md-4"><textarea class="form-control" name="content" rows = "10"  placeholder="text..."></textarea></div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 control-label">custom tags:</div>
                <div class="col-md-4"><input class="form-control" id="tags" type="text" class="tags" name="tags"  /></div>
            </div>
            <div class="form-group row">
                <div class="col-md-4 col-md-offset-4">
                    <button type="submit">submit</button>
                </div>
            </div>   
        </form>
    </div>
</div>
</div>
<script>
    $(function () {
        $('#tags').tagsInput({width: 'auto'});
    });
</script>
</body>
</html>

