<?php
class EditBlog{
    private $blogId;
    private $mysqliExt;
    private $userId;
    
    public function __construct($blog,$mysqliExt)
    {
        $this->blogId=$blog;
        $this->mysqliExt=$mysqliExt;
    }

    public function list_blog_info(){
        $mysqliExt=$this->mysqliExt;
        $blogId=$this->blogId;
        $sql = "select * from blog left join index_column on blog.idx_column_id=index_column.id where blog.id=?";
        $para=array("i",&$blogId);
        $data=$mysqliExt->select_execute($sql,$para);
        return $data;
    }
    
    public function list_idx_columns(){
        $mysqliExt=$this->mysqliExt;
        $sql = "select * from index_column";
        $data=$mysqliExt->select_execute($sql);
        return $data; 
    }
    
    
    public function authority_check() // use for update blog
    {
        $blogId=$this->blogId;
        $userId = $this->userId;
        $mysqliExt = $this->mysqliExt;
        $sql = "select user_id from blog where id=?";
        $para=array("i",&$blogId);
        $data=$mysqliExt->select_execute($sql,$para);
        if ($data != NULL)
        {
            foreach ($data as $value)
            {
                $sqlUserId = $value['user_id'];
            }
            if ($sqlUserId == $userId)
            {
                return 1; // authority permission
            }
            else
            {
                 exit("sorry, you don't have the authority to do this operation!");// authority denied
            }
        }else{
            // the blog id doesn't exist in db
            exit("sorry, the action can't be executed");
        }
    }
    
    public function get_user_id($email)
    {
        $mysqliExt = $this->mysqliExt;
        $sql = "select id from user where email=?";
        $para=array('s',&$email);
        $data=$mysqliExt->select_execute($sql,$para);
        foreach ($data as $value)
        {
            $return = $value['id'];
        }
        $this->userId = $return;
    }
    
    public function update_blog($idxColumnId,$title,$content){
        $mysqliExt = $this->mysqliExt;
        $blogId=$this->blogId;
        $postTime=date("Y-m-d H:i:s");
        $sql = "update blog set idx_column_id=$idxColumnId,title=\"".$title."\",content=\"".$content."\",post_time=\"".$postTime."\" where id=$blogId";
        $mysqliExt->update_execute($sql);
        header("Location:http://".$_SERVER['SERVER_NAME']."/OurBlog/admin/blog_manage.php");
    }
    
}
?>

