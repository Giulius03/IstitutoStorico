<?php
require_once '/bootstrap.php';

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    try {
        $dbh->deleteMenuItem($_GET['id']);
        header('Location: ' . CONTENT_EDITORS_SCRIPT_PATH . 'modifyMenu.php?id='.$_GET['idMenu']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>