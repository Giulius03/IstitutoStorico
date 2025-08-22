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

if (isset($_GET['cont']) && isset($_GET['string'])) {
    $contents = $dbh->getContentByName($_GET['cont'], $_GET['string']);
}

header('Content-Type: application/json');
echo json_encode($contents);
?>