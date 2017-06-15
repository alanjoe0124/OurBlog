<?php
if (!isset($permission))
{
    exit("Permission denied");
}
?>
<div class="headbox">
    <div class="head-side-box"></div>
    <div class="head-main-box">
        <div class="head-title">
            <p><h3><a href="http://localhost/Ourblog/index.php">OurBlog</a></h3>
            <?php
            session_start();
            if (!isset($_SESSION['uid']))
            {
                echo '&nbsp;<h4><a href="http://localhost/Ourblog/admin/login.php">login</a></h4>
        |<h4><a href="http://localhost/Ourblog/admin/register.php">register</a></h4>';
            }
            else
            {
                echo '&nbsp;&nbsp;<h3><a href="http://localhost/Ourblog/admin/blog_manage.php">admin</a></h3>';
            }
            $columns = array();
            $columnRows = Mysql::getInstance()->selectAll("SELECT * FROM index_column");
            foreach ($columnRows as $columnRow)
            {
                $columns[$columnRow["id"]] = $columnRow["name"];
            }
            foreach ($columns as $key => $value)
            {
                echo '&nbsp;&nbsp;&nbsp;<h4><a href="http://localhost/Ourblog/index.php?col=' . $key . '">' . $value . '</a></h4>';
            }
            ?>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="head-search">
            <form method="GET" action="search.php">
                <h5>Search by tag</h5>  <input type="text" name="tag">&nbsp;
                <button type="submit" class="btn btn-default">submit</button>
            </form>
        </div>
        </p>
        <HR width="100%">
    </div>
</div>
