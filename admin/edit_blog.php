<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("UNDEFINED BLOG");
    }
    $blogId = filter_var($_GET['blog'], FILTER_VALIDATE_INT, array(
        'options' => array('min_range' => 1, 'max_range' => 4294967295)
    ));
    if (!$blogId) {
        throw new InvalidArgumentException('Invalid blog id');
    }
} catch (InvalidArgumentException $e) {
    exit("INVALID PARAM");
}

$editBlog = new EditBlog();
$editBlog->authority_check($blogId);
?>
<html>
    <?php
    require_once __DIR__ . '/../common/html/admin_head.html';
    ?>

    <!--contetn_body start-->
    <div class="sidebox"></div>
    <div class="mainbox">
        <form  method="post" action="edit_blog_handle.php">

            <div class="row-title">
                column:
                <select name="column">
                    <?php
                    $blogInfo = $editBlog->list_blog_detail($blogId);
                    $columns = $editBlog->list_columns();
                    foreach ($columns as $key => $value) {
                        if ($blogInfo['idx_column_id'] == $value['id']) {
                            echo '<option value="' . $value['id'] . '" selected="selected"> ' . $value['name'] . '</option>';
                        } else {
                            echo '<option value="' . $value['id'] . '" > ' . $value['name'] . '</option>';
                        }
                    }
                    ?>
                </select>

            </div>
            <div class="row-title">
                title:<input type="text"  id="title" name="title"  value="<?php echo htmlspecialchars($blogInfo['title']); ?>" >
            </div>
            <div class="row-text">
                text:<textarea name="content" rows = "10"> <?php echo htmlspecialchars($blogInfo['content']); ?></textarea>
            </div>
            recommend tag:
            <div class="row-title">
                <?php
                $recommendTag = $editBlog->list_recommend_tag();
                $blogTags = $editBlog->return_blog_tag($blogId);
                foreach ($recommendTag as $vlue) {
                    $recommendTagArr[$vlue['id']] = $vlue['tag_name'];
                }
                foreach ($blogTags as $val) {
                    $blogTagArr[] = $val['tag_name'];
                }
                foreach ($recommendTagArr as $key => $vl) {
                  
                    if(!empty($blogTagArr)){
                       if (in_array($vl, $blogTagArr)) {// if sys tag in blog tags, checked = true; else no checked                      
                           echo '<label><input name="recommend_tag[]" checked="true"  type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                       }else{
                           echo '<label><input name="recommend_tag[]"   type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                       }    
                    }else {
                        echo '<label><input name="recommend_tag[]"   type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                    }
                }
                ?>
            </div>
            <p>custom tag:(input tags separated with space)</p>
            <div class="row-tags">
                <textarea name="custom_tags" rows = "7"  value="">
                    <?php
                    if (!empty($blogTagArr)) {
                        foreach ($blogTagArr as $v) {
                            if (!in_array($v, $recommendTagArr)) {
                                echo htmlspecialchars($v) . ' ';
                            }
                        }
                    }
                    ?>
                </textarea>
            </div>
            <div class="row-title">
                <input type="hidden" name='blog' value="<?php echo $blogId; ?>">
                <input type="submit" name='submit' value="submit">
            </div>   
        </form>

    </div>
    <div class="sidebox"></div>
    <!--contetn_body end-->
</div>
</body>
</html>

