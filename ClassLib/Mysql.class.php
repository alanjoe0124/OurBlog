<?php

Class Mysql {

    private $mysql;
    protected static $_instance;

    private function __construct() {
        $this->mysql = new pdo(
                'mysql:host=localhost;dbname=ourblog', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
        );
    }

    private function __clone() {
        
    }
    
    public static function getInstance() {
        if (!(self::$_instance instanceof self)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
    
    public function startTrans(){
        $this->mysql->beginTransaction();
    }
    
    public function commit(){
        $this->mysql->commit();
    }
    
    public function rollback(){
        $this->mysql->rollBack();
    }

    public function select($table, $data = array(), $where = array()) {
        // select * from table where id=? AND name=?...
        $column = "*";
        if ($data != NULL) {
            $column = implode(',', array_values($data));
        }
        $valuesPlaceholder = "WHERE ";
        $n = 0;
        foreach ($where as $v) {
            if ($n > 0) {
                $valuesPlaceholder .= " AND ";
            }
            $valuesPlaceholder .= " {$v}=?";
            $n++;
        }
        $sql = "SELECT {$column} FROM {$table} {$valuesPlaceholder}";
        return $sql;
    }

    protected function select_execute($sql, $bind = array()) {
        $stmt = $this->mysql->prepare($sql);
        $stmt->execute($bind);
        return $stmt;
    }

    public function selectAll($sql, $bind = array()) {
        return $this->select_execute($sql, $bind)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function selectRow($sql, $bind = array()) {
        return $this->select_execute($sql, $bind)->fetch(PDO::FETCH_ASSOC);
    }

    public function insert($table, $data = array()) {
        //insert into table (v,v,v) values (?,?,?)
        $columns = implode(',', array_keys($data));
        $values = array_values($data);
        $valuesPlaceholder = '?' . str_repeat(',?', count($values) - 1);

        $sql = "INSERT INTO $table ($columns) VALUES ($valuesPlaceholder)";
        $stmt = $this->mysql->prepare($sql);
        $stmt->execute($values);
    }

    public function getLastInsertId() {
        return $this->mysql->lastInsertId();
    }

    public function delete($table, $bind = array()) {
        //  DELETE FROM TABLE WHERE XX = ? AND XX = ?
        $placeholder = array_keys($bind);
        $where = "WHERE ";
        $n = 0;
        foreach($placeholder as $value){
            if($n > 0){
                $where.=" AND ";
            }
            $n++;
            $where.="$value = ?";
        }
        $sql = "DELETE FROM ".$table. " ".$where;
        $arrVal = array_values($bind);
        $this->mysql->prepare($sql)->execute($arrVal);
    }

    public function update($table, $bind = array(), $where = array()) {
        //  $sql = "update $table set idx_column_id = ?,title = ?,content = ?,post_time = ? where id = ?";
        $n = 0;
        $valuesPlaceholder = "";
        foreach ($bind as $k => $v) {
            if ($n > 0) {
                $valuesPlaceholder .= ",";
            }
            $valuesPlaceholder .= "$k = ?";
            $n ++;
        }
        $m = 0;
        $placeholder = "";
        if ($where) {
            $placeholder = " WHERE ";
            foreach ($where as $kw => $vw) {
                if ($m > 0) {
                    $placeholder .= " AND ";
                }
                $placeholder .= "$kw = ?";
                $m ++;
            }
            $bind = array_merge($bind, $where);
        }
        $sql = "UPDATE $table SET " . $valuesPlaceholder . $placeholder;
        $val = array_values($bind);
        $this->mysql->prepare($sql)->execute($val);
    }

}
