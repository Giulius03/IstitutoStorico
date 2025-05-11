<?php
require_once '../../bootstrap.php';

$collectionElems = [];
if (isset($_GET['id'])) {
    $collectionElems["bibliografia"] = $dbh->getBibliographyElementsFromPageID($_GET['id']);
    $collectionElems["cronologia"] = $dbh->getChronologyElementsFromPageID($_GET['id']);
    $collectionElems["emeroteca"] = $dbh->getNewsPaperLibraryElementsFromPageID($_GET['id']);
    $collectionElems["fototeca"] = $dbh->getPhotoLibraryElementsFromPageID($_GET['id']);
    $collectionElems["rete"] = $dbh->getNetworkResourcesFromPageID($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($collectionElems);
?>