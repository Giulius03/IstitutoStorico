<?php
require_once '../../bootstrap.php';

if (isset($_GET['ordBy'])) {
    $inventoryItems = $dbh->getInventoryItems($_GET['ordBy']);
}

header('Content-Type: application/json');
echo json_encode($inventoryItems);
?>