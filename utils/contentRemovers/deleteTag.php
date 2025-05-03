<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deleteTag($_GET['id']);
        header('Location: ../../admin.php?cont=Tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>