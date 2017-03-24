<?php

class Index {

    public function list_columns() {
        $sql = "select * from index_column";
        $data = Mysql::getInstance()->selectAll($sql);
        return $data;
    }

    public function list_blogs($col=NULL) {
        $sql = "select * from blog";
        $data = Mysql::getInstance()->selectAll($sql);
        if ($col != NULL) {
            $sql = "select * from blog where idx_column_id=?";
            $data = Mysql::getInstance()->selectAll($sql, array($col));
        }
        return $data;
    }

}
?>

