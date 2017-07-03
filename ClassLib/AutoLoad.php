<?php
date_default_timezone_set("Asia/Shanghai");
function  __autoload($className) {  
    $filePath = dirname(__FILE__)."/{$className}.class.php";  
    require_once($filePath);  

}  
?>