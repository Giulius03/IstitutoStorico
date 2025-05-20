<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Voce dell'Indice";
$templateParams["nome"] = "../../template/pageComponents.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteIndexItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["componentName"] = "voce dell'indice";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getIndexItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}
$templateParams["modalText"] = "L'eliminazione di questa voce dell'indice sarà permanente.";

require '../../template/base.php';
?>