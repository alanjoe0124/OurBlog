<?php
require_once __DIR__ . "/../ClassLib/AutoLoad.php";
$session = new Session();
if (!$session->isLogin()) {
    header("Location:/admin/login.php");
    exit;
}
?>
<html>
   <?php
    require_once __DIR__.'/../common/html/admin_head.html';
   ?>
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <form  method="post" action="write_blog_handle.php">

                    <div class="row-title">
                        column:
                        <select name="column">
                            <option value="0" selected="selected">select one please</option>
                            <?php
                            $writeBlog = new WriteBlog();
                            foreach ($writeBlog->list_columns()as $key => $value) {
                                echo "<option value=\"{$value['id']}\" > {$value['name']}</option>";
                            }
                            ?>
                        </select>

                    </div>
                    <div class="row-title">
                        title:<input type="text"  id="title" name="title"  value="" placeholder="title...">
                    </div>
                    <div class="row-text">
                        text:<textarea name="content" rows = "10"  placeholder="text..."></textarea>
                    </div>
                    <div class="row-title">
                        tag:
                        <?php
                        foreach ($writeBlog->list_recommend_tag() as $vl) {
                            echo '<label><input name="recommend_tag[]" type="checkbox" value="' . $vl['id'] . '"/>' . $vl['tag_name'] . "</label>";
                        }
                        ?>
                    </div>
                    <div class="row-tags">
                        <p>custom tag:</p>
                        <textarea name="custom_tags" rows = "7"  value="" placeholder="input your tags separated with space"></textarea>
                    </div>
                    <div class="row-title">
                        <input type="submit" name='submit' value="submit">
                    </div>   
                </form>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

