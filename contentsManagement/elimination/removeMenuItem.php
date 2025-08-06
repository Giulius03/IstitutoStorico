<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Voce del Menù";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteMenuItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "voce del menù";

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    $templateParams["content"] = $dbh->getMenuItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idMenu=".$_GET['idMenu'];
}
$templateParams["modalText"] = "L'eliminazione di questa voce del menù sarà permanente.";

require '../../template/base.php';
?>