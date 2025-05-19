<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Elemento di Raccolta";
$templateParams["nome"] = "../../template/collectionElementTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteCollectionElement.php";
$templateParams["css"] = "../../css/style.css";
if (isset($_GET['id']) && isset($_GET['type'])) {
    $templateParams["element"] = $dbh->getCollectionElementFromID($_GET['id'], $_GET['type']);
    $templateParams["actionFile"] .= "?id=".$_GET["id"]."&type=".$_GET["type"]."&idCollection=".$templateParams["element"][0]["raccolta"]."&idPage=".$_GET["idPage"]; 
}
$templateParams["modalText"] = "L'eliminazione di questo elemento sarà permanente.";

require '../../template/base.php';
?>