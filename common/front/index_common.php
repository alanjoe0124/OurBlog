<div class="headbox">
    <div class="head-side-box"></div>
    <div class="head-main-box">
        <div class="head-title">
        <p><h1><a href="/index.php">OurBlog</a></h1>
        <?php
        $session = new Session();
        if (!$session->isLogin()) {
        echo '&nbsp;<h4><a href="/admin/login.php">login</a></h4>
        |<h4><a href="/admin/register.php">register</a></h4>';
        }else{
        echo '&nbsp;&nbsp;<h1><a href="/admin/blog_manage.php">admin</a></h1>'; 
        }
         ;
        $listColumns = Blog::list_columns();
        foreach($listColumns as $value){
        echo '&nbsp;<h4><a href="index.php?col='.$value['id'].'">'.$value['name'].'</a></h4>';
        }
        ?>
        </div>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <div class="head-search">
        <form method="GET" action="search.php">
             Search by tag<input type="text" name="tag">
             <button type="submit">submit</button>
        </form>
        </div>
        </p>
        <HR width="100%">
    </div>
    <div class="head-side-box"></div>
</div>
