<?php
require_once '/bootstrap.php';

$fieldsName = ['Nome'];

if (checkIsSet($fieldsName)) {
    try {
        $idNewTag = $dbh->addReferenceTool($_POST['Nome']);
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=strumento di corredo');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>