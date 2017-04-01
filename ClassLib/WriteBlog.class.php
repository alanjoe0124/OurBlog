<?php

class WriteBlog extends Blog{

    public function post_blog()
    {
        date_default_timezone_set('Asia/Shanghai');
        Mysql::getInstance()->insert('blog',array(
            'idx_column_id'=>$_POST['column'],
            'title'=>$_POST['title'],
            'content'=>$_POST['content'],
            'user_id'=>$_SESSION['uid'],
            'post_time'=>date("Y-m-d h:i:s")));
        header("Location:/admin/write_blog.php");   
    }
}
?>

