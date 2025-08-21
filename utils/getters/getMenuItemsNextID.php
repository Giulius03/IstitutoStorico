<?php
require_once '/bootstrap.php';

$menuItemsMaxID = $dbh->getMenuItemsNextID();

header('Content-Type: application/json');
echo json_encode(['maxId' => (int)$menuItemsMaxID]);
?>