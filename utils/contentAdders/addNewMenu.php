<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

for ($i=$_POST['idPartenza']; $i < $_POST['idFine']; $i++) {
    array_push($fieldsName, "NomeVoce".$i, "fatherItem".$i, "PosizioneVoce".$i);
}

if (checkIsSet($fieldsName)) {
    try {
        $idNewMenu = $dbh->addMenu($_POST['Nome']);
        for ($i=$_POST['idPartenza']; $i < $_POST['idFine']; $i++) {
            $pageToLink = isset($_POST['linkToPage'.$i]) ? $_POST['linkToPage'.$i] : null;
            $father = isset($_POST['fatherItem'.$i]) ? $_POST['fatherItem'.$i] : null;
            $dbh->addMenuItem($_POST['NomeVoce'.$i], $_POST['PosizioneVoce'.$i], $idNewMenu, $pageToLink, $father);
        }
        header('Location: ../../admin.php?cont=Menù');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>