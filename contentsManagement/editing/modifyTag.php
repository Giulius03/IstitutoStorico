<?php
/**
 * Pagina per modifica di un tag.
 */
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Tag";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editTag.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "tag";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getTagFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>