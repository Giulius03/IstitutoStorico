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

$fieldsName = ['Nome'];
for ($i=$_POST['idPartenza']; $i < $_POST['idFine']; $i++) {
    array_push($fieldsName, "NomeVoce".$i, "fatherItem".$i, "PosizioneVoce".$i);
}

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updateMenu($_GET['id'], $_POST['Nome']);
        for ($i=$_POST['idPartenza']; $i < $_POST['idFine']; $i++) {
            $pageToLink = isset($_POST['linkToPage'.$i]) ? $_POST['linkToPage'.$i] : null;
            $father = (isset($_POST['fatherItem'.$i]) && $_POST['fatherItem'.$i] != "") ? $_POST['fatherItem'.$i] : null;
            $dbh->addMenuItem($i, $_POST['NomeVoce'.$i], $_POST['PosizioneVoce'.$i], $_GET['id'], $pageToLink, $father);
        }
        header('Location: ' . ADMIN_PAGE_PATH . '?cont=menù');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>