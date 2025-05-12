<?php
require_once '../../bootstrap.php';

$numOfTags = $dbh->getNumOfTags();
$fieldsName = ['titolo', 'slug', 'autore', 'content', 'titoloSEO', 'testoSEO', 'chiaviSEO', 'numVoci', 'numNote'];

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updatePage($_GET['id'], $_POST['titolo'], $_POST['slug'], $_POST['content'], isset($_POST['visible']) ? true : false, $_POST['titoloSEO'], $_POST['testoSEO'], $_POST['chiaviSEO'], $_POST['autore']);
        updatePageTags($_GET['id']);
        addNewPageIndexItems($_GET['id']);
        addNewPageNotes($_GET['id']);
        updateDisplayedPages($_GET['id']);
        if ($_GET['type'] == "archivio" && checkIsSet(['dataInizio', 'dataFine'])) {
            $dbh->updateArchivePage($_GET['id'], $_POST['dataInizio'], $_POST['dataFine']);
            updateArchivePageReferenceTools($_GET['id']);
            updateArchivePageInventoryItems($_GET['id']);
        }
        if ($_GET['type'] == "raccolta" && checkIsSet(['nomeRaccolta', 'path'])) {
            $path = $_POST['path'] != "" ? $_POST['path'] : null;
            $dbh->updateResourceCollection($_GET['id'], $_POST['nomeRaccolta'], $path);
            addNewCollectionElements($_GET['id']);
        }
        header('Location: ../../admin.php?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}

function updatePageTags($pageID) {
    $tagsSet = []; 
    for ($i=1; $i <= $GLOBALS['numOfTags']; $i++) { 
        if (isset($_POST['tag'.$i])) {
            $tagsSet[] = (int) $_POST['tag'.$i];
        }
    }
    $GLOBALS['dbh']->updatePageTags($pageID, $tagsSet);
}

function addNewPageIndexItems($pageID) {
    for ($i=0; $i < $_POST['numVoci']; $i++) {
        if (checkIsSet(['TitoloVoce'.$i, 'PosizioneVoce'.$i, 'AncoraDest'.$i])) {
            $pageToLink = isset($_POST['linkToPage'.$i]) ? $_POST['linkToPage'.$i] : null;
            $GLOBALS['dbh']->addIndexItem($_POST['PosizioneVoce'.$i], $pageID, $_POST['AncoraDest'.$i], $pageToLink, $_POST['TitoloVoce'.$i]);
        }
    }
}

function addNewPageNotes($pageID) {
    for ($i=0; $i < $_POST['numNote']; $i++) {
        if (checkIsSet(['Testo'.$i, 'AncoraNota'.$i])) {
            $author = $_POST['autore'.$i] != "" ? $_POST['autore'.$i] : null;
            $GLOBALS['dbh']->addNote($_POST['AncoraNota'.$i], $_POST['Testo'.$i], $pageID, $author);
        }
    }
}

function updateDisplayedPages($containerPageID) {
    $displayedPagesTagsSet = [];
    for ($i=1; $i <= $GLOBALS['numOfTags']; $i++) { 
        if (isset($_POST['tagContenuto'.$i])) {
            $displayedPagesTagsSet[] = (int) $_POST['tagContenuto'.$i];
        }
    }
    $GLOBALS['dbh']->updateDisplayedPages($containerPageID, $displayedPagesTagsSet);
}

function updateArchivePageReferenceTools($pageID) {
    $numOfRefTools = $GLOBALS['dbh']->getNumOfReferenceTools();
    $refToolsSet = [];
    for ($i=1; $i <= $numOfRefTools; $i++) { 
        if (isset($_POST['strumento'.$i])) {
            $refToolsSet[] = (int) $_POST['strumento'.$i];
        }
    }
    $GLOBALS['dbh']->updateArchivePageReferenceTools($pageID, $refToolsSet);
}

function updateArchivePageInventoryItems($pageID) {
    $numOfInvItems = $GLOBALS['dbh']->getNumOfInventoryItems();
    $invItemsSet = [];
    for ($i=1; $i <= $numOfInvItems; $i++) { 
        if (checkIsSet(['articolo'.$i, 'quantita'.$i])) {
            $invItemsSet[] = [
                'articolo' => (int) $_POST['articolo'.$i],
                'quantita' => (int) $_POST['quantita'.$i]
            ];        
        }
    }
    $GLOBALS['dbh']->updateArchivePageInventoryItems($pageID, $invItemsSet);
}

function addNewCollectionElements($pageID) {
    $resourceCollectionID = $GLOBALS['dbh']->getResourceCollectionIDFromPageID($pageID);
    for ($i=0; $i < $_POST['numElems']; $i++) {
        $newCollectionElementID = $GLOBALS['dbh']->addCollectionElement($resourceCollectionID);
        switch ($_POST['elemType'.$i]) {
            case 'bibliografia':
                if (checkIsSet(['citazione'.$i, 'HREF'.$i, 'DOI'.$i])) {
                    $href = $_POST['HREF'.$i] != "" ? $_POST['HREF'.$i] : null;
                    $doi = $_POST['DOI'.$i] != "" ? $_POST['DOI'.$i] : null;
                    $GLOBALS['dbh']->addBibliographyElement($_POST['citazione'.$i], $newCollectionElementID, $resourceCollectionID, $href, $doi);
                }
                break;
            case 'cronologia':
                if (checkIsSet(['data'.$i, 'localita'.$i, 'descrizione'.$i])) {
                    $GLOBALS['dbh']->addChronologyElement($_POST['data'.$i], $newCollectionElementID, $resourceCollectionID, $_POST['localita'.$i], $_POST['descrizione'.$i]);
                }
                break;
            case 'emeroteca':
                if (checkIsSet(['giornale'.$i, 'titolo'.$i, 'dataPubblicazione'.$i])) {
                    $href = $_POST['HREF'.$i] != "" ? $_POST['HREF'.$i] : null;
                    $GLOBALS['dbh']->addNewsPaperLibraryElement($_POST['giornale'.$i], $_POST['dataPubblicazione'.$i], $newCollectionElementID, $resourceCollectionID, $_POST['titolo'.$i], $href);
                }
                break;
            case 'fototeca':
                if (checkIsSet(['descrizione'.$i])) {
                    $GLOBALS['dbh']->addPhotoLibraryElement($_POST['descrizione'.$i], $newCollectionElementID, $resourceCollectionID);
                }
                break;
            case 'rete':
                if (checkIsSet(['tipologia'.$i, 'titolo'.$i, 'fonte'.$i, 'HREF'.$i, 'DOI'.$i])) {
                    $source = $_POST['fonte'.$i] != "" ? $_POST['fonte'.$i] : null;
                    $doi = $_POST['DOI'.$i] != "" ? $_POST['DOI'.$i] : null;
                    $GLOBALS['dbh']->addNetworkResource($_POST['tipologia'.$i], $newCollectionElementID, $resourceCollectionID, $_POST['titolo'.$i], $_POST['HREF'.$i], $source, $doi);
                }
                break;
        }
    }
}
?>