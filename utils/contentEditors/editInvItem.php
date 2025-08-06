<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updateInventoryItem($_GET['id'], $_POST['Nome']);
        header('Location: ../../admin.php?cont=articolo d\'inventario');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>