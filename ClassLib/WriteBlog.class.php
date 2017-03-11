<?php

class WriteBlog {

    private $mysqliExt;
    private $userId;

    public function __construct($mysqliExt)
    {
        $this->mysqliExt = $mysqliExt;
    }

    public function post_blog($indexColumnId, $title, $content)
    {
        $mysqliExt = $this->mysqliExt;
        $userId = $this->userId;
        $postTime = date("Y-m-d h:i:s");
        $sqt = "insert into blog set idx_column_id=?, title= ?,content=?,user_id=?,post_time=?";
        $para = array('issis', &$indexColumnId, &$title, &$content, &$userId, &$postTime);
        $mysqliExt->insert_execute($sqt, $para);
        header("Location:http://" . $_SERVER['SERVER_NAME'] . "/OurBlog/admin/write_blog.php");
    }

    public function list_idx_columns()
    {
        $mysqliExt = $this->mysqliExt;
        $sql = "select * from index_column";
        $data = $mysqliExt->select_execute($sql);
        return $data;
    }

    public function get_user_id($email)
    {
        $mysqliExt = $this->mysqliExt;
        $sql = "select id from user where email=?";
        $para = array('s', &$email);
        $data = $mysqliExt->select_execute($sql, $para);
        foreach ($data as $value)
        {
            $return = $value['id'];
        }
        $this->userId = $return;
    }

}
?>

