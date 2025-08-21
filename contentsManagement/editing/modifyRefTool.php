<?php
/**
 * Pagina per modifica di uno strumento di corredo.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Strumento di Corredo";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editRefTool.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "strumento di corredo";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getReferenceToolFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require BASE_TEMPLATE_PATH;
?>