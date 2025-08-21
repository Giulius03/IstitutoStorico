<?php
require_once '/bootstrap.php';

$fieldsName = ['titolo', 'ancora', 'Posizione', 'linkToPage'];

if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $pageToLink = isset($_POST['linkToPage']) ? $_POST['linkToPage'] : null;
        $dbh->updateIndexItem($_GET['id'], $_POST['titolo'], $_POST['Posizione'], $pageToLink, $_POST['ancora']);
        header('Location: ' . CONTENT_EDITORS_SCRIPT_PATH . 'modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>