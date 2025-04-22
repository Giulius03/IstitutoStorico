<?php
session_start();
define("UPLOAD_DIR", "./upload/");
require_once("utils/functions.php");
require_once("db/database.php");
$dbh = new DatabaseHelper("localhost", "root", "", "bugsburnleyshop", 3306);

if (isUserLoggedIn()) {
    $currentLanguage = isset($_COOKIE["Lang".$_SESSION["username"]]) ? $_COOKIE["Lang".$_SESSION["username"]] : "en";
} else {
    $currentLanguage = "en";
}
?>