<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteInvItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "articolo d'inventario";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getInventoryItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>