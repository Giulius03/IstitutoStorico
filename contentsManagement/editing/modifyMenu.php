<?php
/**
 * Pagina per modifica di un menù.
 */
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Menù";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editMenu.php";
$templateParams["js"] = array("../../js/showNewMenuItemFields.js", "../../js/showCurrentMenuItems.js", "../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "menù";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getMenuFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>