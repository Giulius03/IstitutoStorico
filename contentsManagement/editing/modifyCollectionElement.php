<?php
/**
 * Pagina per modifica di un elemento di raccolta.
 */
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Elemento di Raccolta";
$templateParams["nome"] = TEMPLATE_PATH . "collectionElementTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_URL . "editCollectionElement.php";
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["js"] = array(JS_URL . "logout.js", JS_URL . "contentsManagementNavbarLinks.js");
if (isset($_GET['id']) && isset($_GET['type'])) {
    $templateParams["element"] = $dbh->getCollectionElementFromID($_GET['id'], $_GET['type']);
    $templateParams["actionFile"] .= "?id=".$_GET["id"]."&type=".$_GET["type"]."&idCollection=".$templateParams["element"][0]["raccolta"]."&idPage=".$_GET["idPage"]; 
}

require BASE_TEMPLATE_PATH;
?>