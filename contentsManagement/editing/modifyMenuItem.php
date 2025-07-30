<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Voce del Menù";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editMenuItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js");
$templateParams["noPageType"] = "voce del menù";

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    $templateParams["content"] = $dbh->getMenuItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idMenu=".$_GET['idMenu'];
    $templateParams["otherItems"] = $dbh->getMenuItems($_GET['idMenu']);
}

require '../../template/base.php';
?>