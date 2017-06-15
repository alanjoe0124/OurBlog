<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
if ($_POST) {
    try {
        $blogId = filter_var($_POST['blog'], FILTER_VALIDATE_INT, array(
            'options' => array('min_range' => 1)
        ));
        if (!$blogId) {
            throw new InvalidArgumentException('Invalid blog id');
        }
        session_start();
        $data = Mysql::getInstance()->selectRow("SELECT id FROM blog WHERE id = ? AND user_id = ?", array($blogId, $_SESSION['uid']));
        if (!$data) {
            exit("sorry, permission denied");
        }
        $permission = true;
        require_once __DIR__ . '/../common/admin/validate_blog.php';
    } catch (InvalidArgumentException $e) {
// exit($e->getMessage());
        exit("Param ERROR");
    }

// update blog with url
    Mysql::getInstance()->startTrans();
    try {
        Mysql::getInstance()->update("blog", array(
            'idx_column_id' => $columnId,
            'title' => $_POST['title'],
            'content' => $_POST['content'],
            'user_id' => $_SESSION['uid'],
            'post_time' => date("Y-m-d h:i:s")
                ), array('id' => $blogId));
        

        if (isset($_POST['tags'])) {
            $tagIdArr = array();
            $tags = explode(',', $_POST['tags']);
            foreach ($tags as $tag) {
                $tagRow = Mysql::getInstance()->selectRow("select id from tag where tag_name = ?", array($tag));
                $tagId = $tagRow['id'];
                if (!$tagRow) {
                    Mysql::getInstance()->insert('tag', array('tag_name' => $tag));
                    $tagId = Mysql::getInstance()->getLastInsertId();
                }
                $tagIdArr[] = $tagId;
            }
            $existTagIdArr = array();
            $blogTagRows = Mysql::getInstance()->selectAll("SELECT tag_id FROM blog_tag WHERE blog_id = ?", array($blogId));
            foreach ($blogTagRows as $row) {
                $existTagIdArr[] = $row['tag_id'];
            }
            $diffArr = array_diff($tagIdArr, $existTagIdArr);
            $reDiffArr = array_diff($existTagIdArr, $tagIdArr);
            foreach ($diffArr as $diffTagId) {
                Mysql::getInstance()->insert('blog_tag', array('blog_id' => $blogId, 'tag_id' => $diffTagId));
            }
            foreach ($reDiffArr as $reDiffTagId) {
                Mysql::getInstance()->delete('blog_tag',array('tag_id' => $reDiffTagId));
            }
        }
        Mysql::getInstance()->commit();
    } catch (Exception $e) {
        Mysql::getInstance()->rollback();
        exit('SERVER ERROR');
    }
    header("Location:http://localhost/Ourblog/admin/blog_manage.php");
    exit;
}

try {
    session_start();
    if (!isset($_SESSION['uid'])) {
        header('Location:http://localhost/Ourblog/admin/login.php');
        exit;
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("Invalid blog");
    }
    $blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
} catch (InvalidArgumentException $e) {
    exit("INVALID PARAM");
}

$data = Mysql::getInstance()->selectRow("SELECT id FROM blog WHERE id = ? AND user_id = ?", array($blogId, $_SESSION['uid']));
if (!$data) {
    exit("sorry, permission denied");
}

require_once __DIR__ . '/../common/front/admin_common.php';
?>

<div class="mainbox">
    <form  method="post" action="edit_blog.php">
        <div class="row-title">
            column:
            <select name="column">
                <?php
                if (isset($blogId)) {
                    $blogInfo = Mysql::getInstance()->selectRow("SELECT title, content, idx_column_id FROM blog WHERE id = ?", array($blogId));
                }
                $columnRows = Mysql::getInstance()->selectAll("select id, name from index_column");
                foreach ($columnRows as $row) {
                    $columns[$row['id']] = $row['name'];
                }

                foreach ($columns as $key => $value) {
                    if ($blogInfo['idx_column_id'] == $key) {
                        echo '<option value="' . $key . '" selected="selected"> ' . $value . '</option>';
                    } else {
                        echo '<option value="' . $key . '" > ' . $value . '</option>';
                    }
                }
                ?>
            </select>
        </div>
        <div class="row-title">
            title:<input type="text"  id="title" name="title"  value="<?php echo htmlspecialchars($blogInfo['title']); ?>" >
        </div>
        <div id="content" class="row-text">
            text:<textarea name="content" rows = "10"  placeholder="text..."><?php echo htmlspecialchars($blogInfo['content']); ?></textarea>
        </div>
        <div class="row-tags">
            <p>custom tags:</p>
            <input id="tags" type="text" class="tags" name="tags"  value="
            <?php
                $tagArr = array();
                $rows = Mysql::getInstance()
                ->selectAll('SELECT tag.tag_name as tag_name 
                        FROM blog
                        JOIN blog_tag ON blog.id = blog_tag.blog_id
                        JOIN tag ON blog_tag.tag_id = tag.id
                        WHERE blog.id = ?', array($blogId));
                foreach($rows as $row) {
                    $tagArr[] = $row['tag_name'];
                }
                echo implode(',', $tagArr);
            ?> "/>
        </div>
        <input type="hidden" name='blog' value="<?php echo $blogId; ?>">
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

