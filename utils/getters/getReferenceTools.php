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

if (isset($_GET['ordBy'])) {
    $referenceTools = $dbh->getReferenceTools($_GET['ordBy']);
}
header('Content-Type: application/json');
echo json_encode($referenceTools);
?>