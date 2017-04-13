<div>
            recommend tags:
        </div>
        <div class="row-title">
            <?php
            $recommendTag = $blogExt->list_recommend_tag();
            foreach ($recommendTag as $vlue) {
                echo '<label><input name="recommend_tag[]"   type="checkbox" value="'.$vlue['tag_name'].'"/>'.$vlue['tag_name']."</label>";
            }
            ?>
        </div>
        <div class="row-tags">
            <p>custom tags:</p>
            <input type="text" name="custom_tag" value="" placeholder="tags separated with space"> 
        </div>
        <?php
        if ($blogTagRows = $blogExt->get_usual_tag()) {
            echo '<div>usual tag:</div><div class="row-title">';
            foreach ($blogTagRows as $value) {
                $tagNameRow = Mysql::getInstance()->selectRow('select * from tag where id = ?', array($value['tag_id']));
                $tagName = $tagNameRow['tag_name'];
                echo '<label><input name="usual_tag[]" type="checkbox" value="' . $tagName . '"/>' . $tagName . "</label>";
            }
            echo '</div>';
        }
        $csrf_token = rtrim(file_get_contents('/proc/sys/kernel/random/uuid'));
        Mysql::getInstance()->delete("csrf_token", array('session_uid' => $_SESSION['uid']));
        Mysql::getInstance()->insert("csrf_token", array('session_uid' => $_SESSION['uid'], 'token' => $csrf_token));
        ?>
        <div class="row-title">
            <input type= "hidden" name= "csrf_token" value="<?php echo $csrf_token ?>">
            <input type="submit" name='submit' value="submit">
        </div>   
    </form>

</div>
<div class="sidebox"></div>
<!--contetn_body end-->
</div>
</body>
</html>
