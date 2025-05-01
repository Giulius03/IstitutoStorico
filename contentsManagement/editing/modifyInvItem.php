<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editInvItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "Articoli d'inventario";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getInventoryItemFromID($_GET['id']);
}

require '../../template/base.php';
?>