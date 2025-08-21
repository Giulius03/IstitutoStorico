<?php
require_once '/bootstrap.php';

if (isset($_GET['id']) && isset($_GET['idPage']) && isset($_GET['idCollection']) && isset($_GET['type'])) {
    try {
        $dbh->deleteCollectionElement($_GET['id'], $_GET['type']);
        header('Location: ' . CONTENT_EDITORS_SCRIPT_PATH . 'modifyResourceCollection.php?id='.$_GET['idCollection'].'&idPage='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>