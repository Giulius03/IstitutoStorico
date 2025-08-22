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

if (isset($_GET['id'])) {
    try {
        $dbh->deletePage($_GET['id']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>