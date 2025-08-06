<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome', 'Descrizione'];

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updateTag($_GET['id'], $_POST['Nome'], $_POST['Descrizione']);
        header('Location: ../../admin.php?cont=tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>