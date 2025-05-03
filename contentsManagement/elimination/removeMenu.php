<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Menù";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteMenu.php";
$templateParams["js"] = array("../../js/showCurrentMenuItems.js");
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "menù";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getMenuFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>