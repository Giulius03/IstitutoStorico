<?php
/**
 * Pagina per modifica di una voce dell'indice.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Voce dell'Indice";
$templateParams["nome"] = TEMPLATE_PATH . "pageComponents.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editIndexItem.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["componentName"] = "voce dell'indice";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getIndexItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}

require BASE_TEMPLATE_PATH;
?>