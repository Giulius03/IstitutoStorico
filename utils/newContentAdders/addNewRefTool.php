<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addReferenceTool($_POST['Nome']);
        header('Location: ../../admin.php');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>