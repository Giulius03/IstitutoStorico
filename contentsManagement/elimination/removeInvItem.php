<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteInvItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js");
$templateParams["noPageType"] = "articolo d'inventario";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getInventoryItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}
$templateParams["modalText"] = "L'eliminazione di questo articolo d'inventario sarà permanente.";

require '../../template/base.php';
?>