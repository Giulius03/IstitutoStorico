<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Gestione Contenuti";
$templateParams["nome"] = "adminTemplate.php";
$templateParams["js"] = array("js/showContents.js");
$templateParams["css"] = "css/style.css";

require 'template/base.php';
?>