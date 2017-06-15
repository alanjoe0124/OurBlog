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
        'post_time' => date("Y-m-d h:i:s")
    );

    Mysql::getInstance()->startTrans();
    try {
        Mysql::getInstance()->insert('blog', $paramArr);
        $blogId = Mysql::getInstance()->getLastInsertId();

        if (isset($_POST['tags'])) {
            $tags = explode(',', $_POST['tags']);
            foreach ($tags as $tag) {
                $tagRow = Mysql::getInstance()->selectRow("select id from tag where tag_name = ?", array($tag));
                if (!$tagRow) {
                    Mysql::getInstance()->insert('tag', array('tag_name' => $tag));
                    $tagId = Mysql::getInstance()->getLastInsertId();
                }
                Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $tagRow['id']));
            }
        }
    } catch (Exception $e) {
        Mysql::getInstance()->rollback();
    }
    Mysql::getInstance()->commit();
    header("Location:http://localhost/Ourblog/admin/write_blog.php");
    exit;
}
require_once __DIR__ . '/../common/front/admin_common.php';
?>


<div class="mainbox">
    <form  method="post" action="write_blog.php">
        <div class="row-title">
            column:
            <select name="column">
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
        <div class="row-title">
            title:<input type="text"  id="title" name="title"  value="" placeholder="title...">
        </div>
        <div id="content" class="row-text">
            text:<textarea name="content" rows = "10"  placeholder="text..."></textarea>
        </div>
        <div class="row-tags">
            <p>custom tags:</p>
            <input id="tags" type="text" class="tags" name="tags"  />
        </div>
        <div class="row-title">
            <button type="submit">submit</button>
        </div>   
    </form>
</div>
</div>
<script>
    $(function () {
        $('#tags').tagsInput({width: 'auto'});
    });
</script>
</body>
</html>

