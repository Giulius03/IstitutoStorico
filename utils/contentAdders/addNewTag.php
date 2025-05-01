<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome', 'Descrizione'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addTag($_POST['Nome'], $_POST['Descrizione']);
        header('Location: ../../admin.php?cont=Tag');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>