<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Login";
$templateParams["nome"] = "loginTemplate.php";
$templateParams["js"] = array("js/login.js");

require 'template/base.php';
?>