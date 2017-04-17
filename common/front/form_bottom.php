<?php
if (!isset($blogExtInstance)) {
    exit("Permisson denied");
}
?>
<div>
    recommend tags:
</div>
<div class="row-title">
    <?php
    $recommendTag = $blogExtInstance->list_recommend_tag();
    foreach ($recommendTag as $value) {
        echo '<label><input name="recommend_tag[]"   type="checkbox" value="' . $value['tag_name'] . '"/>' . $value['tag_name'] . "</label>";
    }
    ?>
</div>
<div class="row-tags">
    <p>custom tags:</p>
    <input type="text" name="custom_tag" value="" placeholder="tags separated with space"> 
</div>
<?php
$blogTagRows = $blogExtInstance->get_latest_tag();

if (!empty($blogTagRows)) {
    echo '<div>lastest tag:</div><div class="row-title">';
    foreach ($blogTagRows as $value) {
        echo '<label><input name="latest_tag[]" type="checkbox" value="' . $value . '"/>' . $value . "</label>";
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
    var $check_box_click = function () {
        if ($("#checkURL").attr("checked") == "true") {
            $("#content").toggle();
            $("#url").toggle();
        } else {
            $("#content").toggle();
            $("#url").toggle();
        }
    }
    $(document).ready(function () {
        $("#url").hide();
        $("#content").show();
        $("#checkURL").click($check_box_click);
    });
</script>
</body>
</html>
