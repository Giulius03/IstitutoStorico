<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Strumento di Corredo";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteRefTool.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "strumento di corredo";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getReferenceToolFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}
$templateParams["modalText"] = "L'eliminazione di questo strumento di corredo sarà permanente.";

require '../../template/base.php';
?>