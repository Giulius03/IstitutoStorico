<?php
require_once '../../bootstrap.php';

$fieldsName = ['testo', 'autore', 'ancora'];

if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $dbh->updateNote($_GET['id'], $_POST['testo'], $_POST['autore'], $_POST['ancora']);
        header('Location: ../../contentsManagement/editing/modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>