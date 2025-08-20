<?php
/**
 * Pagina per modifica di una nota.
 */
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Nota";
$templateParams["nome"] = "../../template/pageComponents.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editNote.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["componentName"] = "nota";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getNoteFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}

require '../../template/base.php';
?>