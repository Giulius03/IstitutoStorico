<?php
/**
 * Pagina per modifica di una nota.
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
$templateParams["titolo"] = "Modifica Nota";
$templateParams["nome"] = TEMPLATE_PATH . "pageComponents.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editNote.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["componentName"] = "nota";

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    $templateParams["component"] = $dbh->getNoteFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idPage=".$_GET['idPage'];
}

require BASE_TEMPLATE_PATH;
?>