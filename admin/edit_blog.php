<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $session = new Session();
    if (!$session->isLogin()) {
        header('Location:/admin/login.php');
        exit;
    }
    if (!isset($_GET['blog'])) {
        throw new InvalidArgumentException("Invalid blog");
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

require_once __DIR__ . '/../common/front/admin_common.php';
?>

<!--contetn_body start-->
<div class="mainbox">
    <form  method="post" action="edit_blog_handle.php">

        <div class="row-title">
            column:
            <select name="column">
                <?php
                $blogInfo = $editBlog->list_blog_detail($blogId);
                $columns = $editBlog->list_columns();
                foreach ($columns as $value) {
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
        <input id="checkURL" type="checkbox" >Add URL?
        <div id="url" class="row-title">
            URL<input type="text" name="blog_url" placeholder="http://" value="<?php
            if (!empty($blogInfo['blog_url'])) {
                echo htmlspecialchars($blogInfo['blog_url']);
            }
            ?>">
        </div>
        <div id="content" class="row-text">
            text:<textarea name="content" rows = "10"  placeholder="text..."><?php echo htmlspecialchars($blogInfo['content']); ?></textarea>
        </div>
        <div>
            current tags:
        </div>
        <div class="row-title">
            <?php
            $blogTags = $editBlog->return_blog_tag($blogId); 
            if ($blogTags) {
                foreach ($blogTags as $val) {
                    echo '<label><input name="current_tag[]"  checked="true" type="checkbox" value="'.$val['tag_name'].'"/>'.$val['tag_name']."</label>";
                }
            }
            ?>
        </div> 
        
        <input type="hidden" name='blog' value="<?php echo $blogId; ?>">
<?php
        $blogExt = $editBlog;
        require_once __DIR__ . '/../common/front/form_bottom.php';
?>

