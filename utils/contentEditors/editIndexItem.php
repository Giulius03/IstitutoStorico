<?php
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

$fieldsName = ['titolo', 'ancora', 'Posizione'];

if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idPage'])) {
    try {
        $pageToLink = isset($_POST['linkToPage']) ? $_POST['linkToPage'] : null;
        $dbh->updateIndexItem($_GET['id'], $_POST['titolo'], $_POST['Posizione'], $pageToLink, $_POST['ancora']);
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyPage.php?id='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>