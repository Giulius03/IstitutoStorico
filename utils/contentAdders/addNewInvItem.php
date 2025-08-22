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

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addInventoryItem($_POST['Nome']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=articolo d\'inventario');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>