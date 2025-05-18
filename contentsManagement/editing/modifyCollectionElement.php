<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Elemento di Raccolta";
$templateParams["nome"] = "../../template/collectionElementTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editCollectionElement.php";
$templateParams["css"] = "../../css/style.css";
if (isset($_GET['id']) && isset($_GET['type'])) {
    $templateParams["element"] = $dbh->getCollectionElementFromID($_GET['id'], $_GET['type']);
    $templateParams["actionFile"] .= "?id=".$_GET["id"]."&type=".$_GET["type"]."&idCollection=".$templateParams["element"][0]["raccolta"]."&idPage=".$_GET["idPage"]; 
}

require '../../template/base.php';
?>