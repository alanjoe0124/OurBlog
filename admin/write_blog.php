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
                $writeBlog = new WriteBlog();
                foreach ($writeBlog->list_columns() as $value) {
                    echo "<option value=\"{$value['id']}\" > {$value['name']}</option>";
                }
                ?>
            </select>

        </div>
        <div class="row-title">
            title:<input type="text"  id="title" name="title"  value="" placeholder="title...">
        </div>

        <input id="checkURL" type="checkbox" >Add URL?
        <div id="url" class="row-title">
            URL<input type="text" name="blog_url" placeholder="http://">
        </div>
        <div id="content" class="row-text">
            text:<textarea name="content" rows = "10"  placeholder="text..."></textarea>
        </div>
<?php
        $blogExtInstance = $writeBlog;
        require_once __DIR__ . '/../common/front/form_bottom.php';
?>

