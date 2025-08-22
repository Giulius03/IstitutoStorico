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

$items = [];
if (!isset($_GET['id'])) {
    $items = $dbh->getPrimaryMenu();
} else {
    $items = $dbh->getMenuItemsByFather($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($items);
?>