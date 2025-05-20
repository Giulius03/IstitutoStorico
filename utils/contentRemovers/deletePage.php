<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deletePage($_GET['id']);
        header('Location: ../../admin.php?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>