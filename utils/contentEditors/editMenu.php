<?php
require_once '/bootstrap.php';

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