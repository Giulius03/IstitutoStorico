<?php
require_once '/bootstrap.php';

$collectionElems = [];
if (isset($_GET['collectionID'])) {
    $collectionElems["bibliografia"] = $dbh->getBibliographyElementsFromCollectionID($_GET['collectionID']);
    $collectionElems["cronologia"] = $dbh->getChronologyElementsFromCollectionID($_GET['collectionID']);
    $collectionElems["emeroteca"] = $dbh->getNewsPaperLibraryElementsFromCollectionID($_GET['collectionID']);
    $collectionElems["fototeca"] = $dbh->getPhotoLibraryElementsFromCollectionID($_GET['collectionID']);
    $collectionElems["rete"] = $dbh->getNetworkResourcesFromCollectionID($_GET['collectionID']);
}

header('Content-Type: application/json');
echo json_encode($collectionElems);
?>