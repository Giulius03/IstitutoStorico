<?php
/**
 * Pagina per modifica di un articolo d'inventario.
 */
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editInvItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "articolo d'inventario";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getInventoryItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>