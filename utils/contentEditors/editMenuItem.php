<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome', 'father', 'Posizione'];

if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idMenu'])) {
    try {
        $pageToLink = isset($_POST['linkToPage']) ? $_POST['linkToPage'] : null;
        $father = (isset($_POST['father']) && $_POST['father'] != "") ? $_POST['father'] : null;
        $dbh->updateMenuItem($_GET['id'], $_POST['Nome'], $father, $_POST['Posizione'], $pageToLink);
        header('Location: ../../contentsManagement/editing/modifyMenu.php?id='.$_GET['idMenu']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>