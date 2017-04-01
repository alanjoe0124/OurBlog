<?php

class Index {

    public function list_columns() {
        return Mysql::getInstance()->selectAll("select * from index_column");
    }

    public function list_blogs($col) {
        if(!$col){
            return Mysql::getInstance()->selectAll("select * from blog");   
        }else{
            return Mysql::getInstance()->selectAll("select * from blog where idx_column_id=?", array($col));
        }
    }

}
?>

