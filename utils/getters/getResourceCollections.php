<?php
require_once '/bootstrap.php';

if (isset($_GET['id'])) {
    $resCollections = $dbh->getResourceCollectionsFromPageID($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($resCollections);
?>