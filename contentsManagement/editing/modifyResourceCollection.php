<?php
/**
 * Pagina per modifica di una raccolta di risorse.
 */
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Raccolta di Risorsa";
$templateParams["nome"] = "../../template/resourceCollectionTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editResourceCollection.php";
$templateParams["js"] = array("../../js/logout.js", "../../js/showNewCollectionElemFields.js", "../../js/showCurrentCollectionElements.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["css"] = "../../css/style.css";
if (isset($_GET['id'])) {
    $templateParams["collection"] = $dbh->getResourceCollectionFromID($_GET['id']);
}

require '../../template/base.php';
?>