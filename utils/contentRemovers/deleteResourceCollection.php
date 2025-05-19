<?php
require_once '../../bootstrap.php';

var_dump($_GET);
if (isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $dbh->deleteResourceCollection($_GET['id']);
        header('Location: ../../contentsManagement/editing/modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>