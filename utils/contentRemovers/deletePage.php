<?php
require_once '/bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deletePage($_GET['id']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>