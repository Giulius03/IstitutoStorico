<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Nota";
$templateParams["nome"] = "../../template/pageComponents.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteNote.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js");
$templateParams["componentName"] = "nota";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getNoteFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}
$templateParams["modalText"] = "L'eliminazione di questa nota sarà permanente.";

require '../../template/base.php';
?>