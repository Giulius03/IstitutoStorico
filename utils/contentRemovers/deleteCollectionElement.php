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

if (isset($_GET['id']) && isset($_GET['idPage']) && isset($_GET['idCollection']) && isset($_GET['type'])) {
    try {
        $dbh->deleteCollectionElement($_GET['id'], $_GET['type']);
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyResourceCollection.php?id='.$_GET['idCollection'].'&idPage='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>