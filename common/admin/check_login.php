<?php
session_start();
if (!isset($_SESSION['uid'])) {
    header("Location:http://localhost/Ourblog/admin/login.php");
    exit;
}