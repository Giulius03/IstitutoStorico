<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Voce dell'Indice";
$templateParams["nome"] = "../../template/pageComponents.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editIndexItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["componentName"] = "voce dell'indice";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getIndexItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}

require '../../template/base.php';
?>