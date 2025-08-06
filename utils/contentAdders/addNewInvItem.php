<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addInventoryItem($_POST['Nome']);
        header('Location: ../../admin.php?cont=articolo d\'inventario');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>