<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Raccolta di Risorsa";
$templateParams["nome"] = "../../template/resourceCollectionTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteResourceCollection.php";
$templateParams["js"] = array("../../js/showCurrentCollectionElements.js");
$templateParams["css"] = "../../css/style.css";
if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["collection"] = $dbh->getResourceCollectionFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}
$templateParams["modalText"] = "L'eliminazione di questa raccolta sarà permanente e comporterà anche la cancellazione di tutti gli elementi contenuti al suo interno.";

require '../../template/base.php';
?>