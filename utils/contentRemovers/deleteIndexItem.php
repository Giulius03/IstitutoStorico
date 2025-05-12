<?php
require_once '../../bootstrap.php';

if (isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $dbh->deleteIndexItem($_GET['id']);
        header('Location: ../../contentsManagement/editing/modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>