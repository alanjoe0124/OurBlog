        <?php
        if (!isset($_SESSION)) {
            exit("Permission denied");
        }
        ?>
        <div>
            recommend tags:
        </div>
        <div class="row-title">
            <?php
            $tagNames = Blog::list_recommend_tag();
            foreach ($tagNames as $tagName) {
                echo '<label><input name="recommend_tag[]"   type="checkbox" value="' . $tagName . '"/>' . $tagName . "</label>";
            }
            ?>
        </div>
        <div class="row-tags">
            <p>custom tags:</p>
            <input type="text" name="custom_tag" value="" placeholder="tags separated with space"> 
        </div>
        <?php
        $tagNames = Blog::get_latest_tag($_SESSION['uid']);
        if (!empty($tagNames)) {
            echo '<div>lastest tag:</div><div class="row-title">';
            foreach ($tagNames as $tagName) {
                echo '<label><input name="latest_tag[]" type="checkbox" value="' . htmlspecialchars($tagName) . '"/>' . htmlspecialchars($tagName) . "</label>";
            }
            echo '</div>';
        }
        ?>
        <div class="row-title">
            <button type="submit">submit</button>
        </div>   
    </form>
</div>
<div class="sidebox"></div>
<!--contetn_body end-->
</div>
<script>
    $(document).ready(function () {
        $("#url").hide();
        $("#content").show();
        $("#checkURL").click(function () {
            if ($("#checkURL").prop("checked") === true) {
                $("#content").hide();
                $("#url").show();
            } else {
                $("#content").show();
                $("#url").hide();
            }
        });
    });
</script>
</body>
</html>
