<?php
require_once '../../bootstrap.php';

$fieldsName = ['titolo', 'ancora', 'Posizione', 'linkToPage'];
var_dump($_POST);
var_dump($_GET);
if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $pageToLink = isset($_POST['linkToPage']) ? $_POST['linkToPage'] : null;
        $dbh->updateIndexItem($_GET['id'], $_POST['titolo'], $_POST['Posizione'], $pageToLink, $_POST['ancora']);
        header('Location: ../../contentsManagement/editing/modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>