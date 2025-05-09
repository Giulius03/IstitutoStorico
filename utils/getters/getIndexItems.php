<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    $indexItems = $dbh->getIndexItemsFromPageID($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($indexItems);
?>