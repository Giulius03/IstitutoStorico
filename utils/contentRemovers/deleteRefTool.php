<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deleteReferenceTool($_GET['id']);
        header('Location: ../../admin.php?cont=strumento di corredo');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>