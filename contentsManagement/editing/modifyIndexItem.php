<?php
/**
 * Pagina per modifica di una voce dell'indice.
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
$templateParams["titolo"] = "Modifica Voce dell'Indice";
$templateParams["nome"] = TEMPLATE_PATH . "pageComponents.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_URL . "editIndexItem.php";
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["js"] = array(JS_URL . "logout.js", JS_URL . "contentsManagementNavbarLinks.js");
$templateParams["componentName"] = "voce dell'indice";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getIndexItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}

require BASE_TEMPLATE_PATH;
?>