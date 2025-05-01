<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updateReferenceTool($_GET['id'], $_POST['Nome']);
        header('Location: ../../admin.php?cont=Strumenti di corredo');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>