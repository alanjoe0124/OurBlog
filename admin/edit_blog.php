<?php
require_once __DIR__ . '/../ClassLib/AutoLoad.php';
try {
    $session = new Session();
    if (!($session->isLogin())) {
        header('Location:/admin/login.php');
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
    $editBlog = new EditBlog();
    $editBlog->authority_check($blogId);
} catch (InvalidArgumentException $e) {
    exit("INVALID PARAM");
} catch (Exception $e) {
    exit("SERVER ERROR");
}
?>
<html>
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" type="text/css" href="/common/css/main.css">
    </head>
    <body>
        <div class="container">
            <!--content_head start-->
            <div class="headbox">
                <div class="head-side-box"></div>
                <div class="head-main-box">
                    <p><h1><a href="/index.php">OurBlog</a>/edit_blog</h1>
                    &nbsp;&nbsp;<h4><a href="/admin/blog_manage.php">blog manage</a></h4>
                    &nbsp;&nbsp;<h4><a href="/admin/write_blog.php">blog write</a></h4></p>
                    <HR width="100%">
                </div>
                <div class="head-side-box"></div>
            </div>
            <!--content_head end->
            
            <!--contetn_body start-->
            <div class="sidebox"></div>
            <div class="mainbox">
                <form  method="post" action="edit_blog_handle.php">

                    <div class="row-title">
                        column:
                        <select name="column">
                            <?php $blogInfo = $editBlog->list_blog_info(); ?>
                            <option value="<?php echo $blogInfo['idx_column_id']; ?>" selected="selected"><?php echo $blogInfo['name'] ?></option>
                            <?php
                            $idxColumnList = $editBlog->list_idx_columns();
                            foreach ($idxColumnList as $key => $value) {
                                echo '<option value="' . $value['id'] . '" > ' . $value['name'] . '</option>';
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
                        $sysTag = $editBlog->list_sys_tag();
                        foreach ($sysTag as $value) {
                            $sysTagArr[$value['id']] = $value['tag_name'];
                        }
                        if ($editBlog->return_blog_tag()) {

                            foreach (($editBlog->return_blog_tag()) as $val) {
                                $blogTagArr[] = $val['tag_name'];
                            }

                            foreach ($sysTagArr as $key => $vl) {
                                if (in_array($vl, $blogTagArr)) {// if sys tag in blog tags, checked = true; else no checked                      
                                    echo '<label><input name="sys_tag[]" checked="true"  type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                                } else {
                                    echo '<label><input name="sys_tag[]" type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                                }
                            }
                        } else {
                            foreach ($sysTagArr as $key => $vl) {
                                echo '<label><input name="sys_tag[]" type="checkbox" value="' . $key . '"/>' . $vl . "</label>";
                            }
                        }
                        ?>
                    </div>
                    <p>custom tag:(input tags separated with space)</p>
                    <div class="row-tags">
                        <textarea name="custom_tags" rows = "7"  value="">
                            <?php
                            if ($editBlog->return_blog_tag()) {
                                foreach ($blogTagArr as $v) {
                                    if (!in_array($v, $sysTagArr)) {
                                        echo htmlspecialchars($v) . ' ';
                                    }
                                }
                            } else {
                                
                            }
                            ?>
                        </textarea>
                    </div>
                    <div class="row-title">
                        <input type="hidden" name='blog' value="<?php echo $_GET['blog']; ?>">
                        <input type="submit" name='submit' value="submit">
                    </div>   
                </form>

            </div>
            <div class="sidebox"></div>
            <!--contetn_body end-->
        </div>
    </body>
</html>

