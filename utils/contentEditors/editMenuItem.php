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

$fieldsName = ['Nome', 'father', 'Posizione'];

if (checkIsSet($fieldsName) && isset($_GET['id']) && isset($_GET['idMenu'])) {
    try {
        $pageToLink = isset($_POST['linkToPage']) ? $_POST['linkToPage'] : null;
        $father = (isset($_POST['father']) && $_POST['father'] != "") ? $_POST['father'] : null;
        $dbh->updateMenuItem($_GET['id'], $_POST['Nome'], $father, $_POST['Posizione'], $pageToLink);
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyMenu.php?id='.$_GET['idMenu']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>