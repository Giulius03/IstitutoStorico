<?php
require_once '../../bootstrap.php';

$numOfTags = $dbh->getNumOfTags();
$tagsSet = [];
for ($i=1; $i <= $numOfTags; $i++) { 
    if (isset($_POST['tag'.$i])) {
        $tagsSet[] = (int) $_POST['tag'.$i];
    }
}

$displayedPagesTagsSet = [];
for ($i=1; $i <= $numOfTags; $i++) { 
    if (isset($_POST['tagContenuto'.$i])) {
        $displayedPagesTagsSet[] = (int) $_POST['tagContenuto'.$i];
    }
}

$fieldsName = ['pageType', 'titolo', 'slug', 'visible', 'content', 'titoloSEO', 'testoSEO', 'chiaviSEO', 'numVoci', 'numNote'];
if (checkIsSet($fieldsName)) {
    try {
        $idNewPage = $dbh->addPage($_POST['titolo'], $_POST['slug'], $_POST['content'], isset($_POST['visible']) ? true : false, $_POST['titoloSEO'], $_POST['testoSEO'], $_POST['chiaviSEO']);
        // inserimento tag della pagina
        $dbh->connectTagsToPage($idNewPage, $tagsSet);
        //inserimento voci dell'indice della pagina
        for ($i=0; $i < $_POST['numVoci']; $i++) {
            if (checkIsSet(['TitoloVoce'.$i, 'PosizioneVoce'.$i, 'AncoraDest'.$i])) {
                $pageToLink = isset($_POST['linkToPage'.$i]) ? $_POST['linkToPage'.$i] : null;
                $dbh->addIndexItem($_POST['PosizioneVoce'.$i], $idNewPage, $_POST['AncoraDest'.$i], $pageToLink, $_POST['TitoloVoce'.$i]);
            }
        }
        //inserimento note della pagina
        for ($i=0; $i < $_POST['numNote']; $i++) {
            if (checkIsSet(['Testo'.$i, 'AncoraNota'.$i])) {
                $author = $_POST['autore'.$i] != "" ? $_POST['autore'.$i] : null;
                $dbh->addNote($_POST['AncoraNota'.$i], $_POST['Testo'.$i], $idNewPage, $author);
            }
        }
        //inserimento pagine contenute tramite tag
        $dbh->addDisplayedPages($idNewPage, $displayedPagesTagsSet);
        header('Location: ../../admin.php?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>