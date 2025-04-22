<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Login";
$templateParams["nome"] = "loginTemplate.php";
$templateParams["js"] = array("js/login.js");
//$templateParams["css"] = array("css/adjustments.css");
//$templateParams["onloadFunctions"] = "checkNotifications('$currentLanguage'); getArticlesData(true, '$currentLanguage'); getArticlesData(false, '$currentLanguage')";

require 'template/base.php';
?>