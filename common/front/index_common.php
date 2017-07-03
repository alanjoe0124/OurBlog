<?php
if (!isset($permission)) {
    exit("Permission denied");
}
?>

<div class="row">
    <div class="col-md-12">
        <div class="col-md-8 col-md-offset-2">
            <p><h3><a href="http://localhost/Ourblog/index.php">OurBlog</a></h3>
            <?php
            session_start();
            if (!isset($_SESSION['uid'])) {
                echo '&nbsp;<h4><a href="http://localhost/Ourblog/admin/login.php">login</a></h4>
        |<h4><a href="http://localhost/Ourblog/admin/register.php">register</a></h4>';
            } else {
                echo '&nbsp;&nbsp;<h3><a href="http://localhost/Ourblog/admin/blog_manage.php">admin</a></h3>';
            }
            $columns = array();
            $columnRows = Mysql::getInstance()->selectAll("SELECT * FROM index_column");
            foreach ($columnRows as $columnRow) {
                $columns[$columnRow["id"]] = $columnRow["name"];
            }
            foreach ($columns as $key => $value) {
                echo '&nbsp;&nbsp;&nbsp;<h4><a href="http://localhost/Ourblog/index.php?col=' . $key . '">' . $value . '</a></h4>';
            }
            ?>
        </p>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <form method="GET" action="search.php">
            <div class="form-group">
                <div class="col-md-2 control-label">Search by tag:</div>  
                <div class="col-md-4"><input class="form-control" type="text" name="tag"></div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-default">submit</button>
            </div>
        </form>
        <HR width="100%">
    </div>
</div>

