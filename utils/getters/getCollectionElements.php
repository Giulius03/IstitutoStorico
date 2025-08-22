<?php
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

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