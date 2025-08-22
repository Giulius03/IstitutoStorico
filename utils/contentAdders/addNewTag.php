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

$fieldsName = ['Nome', 'Descrizione'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addTag($_POST['Nome'], $_POST['Descrizione']);
        header('Location: ' . ADMIN_PAGE_URL . '?cont=tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>