<?php
require_once '../../bootstrap.php';

$fieldsName = ['Nome'];

for ($i=0; $i < $_POST['numeroVoci']; $i++) {
    $current = $i+1;
    array_push($fieldsName, "NomeVoce".$current, "fatherItem".$current, "PosizioneVoce".$current);
}

if (checkIsSet($fieldsName)) {
    try {
        $idNewMenu = $dbh->addMenu($_POST['Nome']);
        for ($i=0; $i < $_POST['numeroVoci']; $i++) {
            $current = $i+1;
            $pageToLink = isset($_POST['linkToPage'.$current]) ? $_POST['linkToPage'.$current] : null;
            $father = isset($_POST['fatherItem'.$current]) ? $_POST['fatherItem'.$current] : null;
            $dbh->addMenuItem($_POST['NomeVoce'.$current], $_POST['PosizioneVoce'.$current], $idNewMenu, $pageToLink, $father);
        }
        header('Location: ../../admin.php?cont=Menù');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>