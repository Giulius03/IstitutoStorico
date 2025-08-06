<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Menù";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteMenu.php";
$templateParams["js"] = array("../../js/showCurrentMenuItems.js", "../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "menù";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getMenuFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}
$templateParams["modalText"] = "L'eliminazione di questo menù sarà permanente e comporterà anche la cancellazione di tutte le sue voci.";

require '../../template/base.php';
?>