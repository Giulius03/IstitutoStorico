<?php
/**
 * Pagina per modifica di una raccolta di risorse.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Raccolta di Risorsa";
$templateParams["nome"] = TEMPLATE_PATH . "resourceCollectionTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editResourceCollection.php";
$templateParams["js"] = array(JS_PATH."logout.js", JS_PATH."showNewCollectionElemFields.js", JS_PATH."showCurrentCollectionElements.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["css"] = "../../css/style.css";
if (isset($_GET['id'])) {
    $templateParams["collection"] = $dbh->getResourceCollectionFromID($_GET['id']);
}

require BASE_TEMPLATE_PATH;
?>