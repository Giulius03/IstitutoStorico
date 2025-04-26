<?php
require_once '../../bootstrap.php';

$inventoryItems = $dbh->getInventoryItems();

header('Content-Type: application/json');
echo json_encode($inventoryItems);
?>