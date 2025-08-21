<?php
require_once '/bootstrap.php';

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