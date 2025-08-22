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

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    try {
        $dbh->deleteMenuItem($_GET['id']);
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyMenu.php?id='.$_GET['idMenu']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>