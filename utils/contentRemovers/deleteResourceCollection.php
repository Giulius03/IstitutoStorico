<?php
require_once '/bootstrap.php';

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $dbh->deleteResourceCollection($_GET['id']);
        header('Location: ' . CONTENT_EDITORS_SCRIPT_PATH . 'modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>