<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deleteInventoryItem($_GET['id']);
        header('Location: ../../admin.php?cont=articolo d\'inventario');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>