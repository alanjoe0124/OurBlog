<?php
class BlogDetail{
    private $blogId;
    private $mysqliExt;
    
    public function __construct($blog,$mysqliExt)
    {
        $this->blogId=$blog;
        $this->mysqliExt=$mysqliExt;
    }
    
    public function list_columns(){
        $mysqliExt = $this->mysqliExt;
        $sql = "select * from index_column";
        $data=$mysqliExt->select_execute($sql);
        return $data;
    }
    
    public function list_blog_detail(){
        $blogId=$this->blogId;
        $mysqliExt = $this->mysqliExt; 
        $sql = "select * from blog where id=?";
        $para=array('i',&$blogId);
        $data=$mysqliExt->select_execute($sql,$para);
        return $data;
    }
    
}
?>