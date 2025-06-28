<?php
require_once '../../bootstrap.php';

$items = [];
if (!isset($_GET['id'])) {
    $items = $dbh->getPrimaryMenu();
} else {
    $items = $dbh->getMenuItemsByFather($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($items);
?>