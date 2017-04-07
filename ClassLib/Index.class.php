<?php

class Index extends Blog{

    public function list_blogs($col) {
        if(!$col){
            return Mysql::getInstance()->selectAll("select * from blog");   
        }
        return Mysql::getInstance()->selectAll("select * from blog where idx_column_id = ? ", array($col));

    }

}
?>

