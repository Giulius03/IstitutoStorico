<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Raccolta di Risorsa";
$templateParams["nome"] = "../../template/resourceCollectionTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editResourceCollection.php";
$templateParams["js"] = array("../../js/showNewCollectionElemFields.js");
$templateParams["css"] = "../../css/style.css";
// , "../../js/showCurrentCollectionElements.js"
if (isset($_GET['id'])) {
    $templateParams["collection"] = $dbh->getResourceCollectionFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>