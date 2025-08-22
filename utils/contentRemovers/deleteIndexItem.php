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

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $dbh->deleteIndexItem($_GET['id']);
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>