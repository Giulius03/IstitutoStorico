<?php
require_once '../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuovo Menù";
$templateParams["nome"] = "../template/noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["js"] = array("../js/showNewMenuItemFields.js");
$templateParams["css"] = "../css/style.css";
$templateParams["noPageType"] = "menù";

require '../template/base.php';
?>