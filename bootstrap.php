<?php
session_start();
define("UPLOAD_DIR", "./upload/");
require_once("db/dbConfig.php");
$dbh = new DatabaseHelper();
?>