<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deleteAllMenuItems($_GET['id']);
        $dbh->deleteMenu($_GET['id']);
        header('Location: ../../admin.php?cont=Menù');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>