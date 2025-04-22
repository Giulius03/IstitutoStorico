<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Istituto storico della Resistenza e dell'Età contemporanea di Forlì-Cesena";
$templateParams["nome"] = "loginTemplate.php";
$templateParams["js"] = array("js/goToLogin.js");
//$templateParams["css"] = array("css/adjustments.css");
//$templateParams["onloadFunctions"] = "checkNotifications('$currentLanguage'); getArticlesData(true, '$currentLanguage'); getArticlesData(false, '$currentLanguage')";

require 'template/base.php';
?>