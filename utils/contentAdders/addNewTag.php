<?php
require_once '/bootstrap.php';

$fieldsName = ['Nome', 'Descrizione'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addTag($_POST['Nome'], $_POST['Descrizione']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>