<?php
require_once '/bootstrap.php';

if (isset($_GET['id'])) {
    try {
        $dbh->deleteTag($_GET['id']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>