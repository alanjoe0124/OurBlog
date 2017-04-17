<?php
require_once __DIR__ . "/../ClassLib/AutoLoad.php";
$session = new Session();
if (!$session->isLogin()) {
    header("Location:/admin/login.php");
    exit;
}
require_once __DIR__ . '/../common/front/admin_common.php';
?>

<!--contetn_body start-->
<div class="mainbox">
    <form  method="post" action="write_blog_handle.php">

        <div class="row-title">
            column:
            <select name="column">
                <option value="0" selected="selected">select one please</option>
                <?php
                foreach (Blog::list_columns() as $key => $value) {
                    echo "<option value=\"$key\" > $value</option>";
                }
                ?>
            </select>

        </div>
        <div class="row-title">
            title:<input type="text"  id="title" name="title"  value="" placeholder="title...">
        </div>

        <label><input id="checkURL" type="checkbox" >Add URL?</label>
        <div id="url" class="row-title">
            URL<input type="text" name="blog_url" placeholder="http://">
        </div>
        <div id="content" class="row-text">
            text:<textarea name="content" rows = "10"  placeholder="text..."></textarea>
        </div>
<?php
        $blogExtInstance = new WriteBlog();
        require_once __DIR__ . '/../common/front/form_bottom.php';
?>

