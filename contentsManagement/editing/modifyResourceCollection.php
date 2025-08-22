<?php
/**
 * Pagina per modifica di una raccolta di risorse.
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
$templateParams["titolo"] = "Modifica Raccolta di Risorsa";
$templateParams["nome"] = TEMPLATE_PATH . "resourceCollectionTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editResourceCollection.php";
$templateParams["js"] = array(JS_URL."logout.js", JS_URL."showNewCollectionElemFields.js", JS_URL."showCurrentCollectionElements.js", JS_URL."contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_URL."style.css";
if (isset($_GET['id'])) {
    $templateParams["collection"] = $dbh->getResourceCollectionFromID($_GET['id']);
}

require BASE_TEMPLATE_PATH;
?>