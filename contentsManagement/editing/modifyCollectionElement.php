<?php
/**
 * Pagina per modifica di un elemento di raccolta.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Elemento di Raccolta";
$templateParams["nome"] = TEMPLATE_PATH . "collectionElementTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editCollectionElement.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
if (isset($_GET['id']) && isset($_GET['type'])) {
    $templateParams["element"] = $dbh->getCollectionElementFromID($_GET['id'], $_GET['type']);
    $templateParams["actionFile"] .= "?id=".$_GET["id"]."&type=".$_GET["type"]."&idCollection=".$templateParams["element"][0]["raccolta"]."&idPage=".$_GET["idPage"]; 
}

require BASE_TEMPLATE_PATH;
?>