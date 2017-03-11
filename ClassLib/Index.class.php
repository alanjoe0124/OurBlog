<?php
class Index{
    private $mysqliExt;
    
    public function __construct($mysqliExt)
    {
        $this->mysqliExt=$mysqliExt;
    }
    
    public function list_columns(){
        $mysqliExt = $this->mysqliExt;
        $sql = "select * from index_column";
        $data=$mysqliExt->select_execute($sql);
        return $data;
    }
    
    public function list_blogs($col=NULL){
        $mysqliExt = $this->mysqliExt;
        if($col==NULL){
          $sql = "select * from blog";
        }else{
          $sql = "select * from blog where idx_column_id=?";
          $para=array('i',&$col);
        }
        $data=$mysqliExt->select_execute($sql,$para);
        return $data;
    }
          
    
}
?>

