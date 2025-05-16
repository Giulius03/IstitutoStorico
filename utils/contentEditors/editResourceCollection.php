<?php
require_once '../../bootstrap.php';

$fieldsName = ['idPage', 'idCollection', 'numElems', 'Nome', 'path'];

if (checkIsSet($fieldsName)) {
    try {
        $dbh->updateResourceCollection($_POST['idCollection'], $_POST['Nome'], $_POST['path']);
        if (isset($_POST['elemType']) && $_POST['numElems'] > 0) {
            addNewCollectionElements($_POST['idCollection']);
        }
        header('Location: ../../contentsManagement/editing/modifyPage.php?id='.$_POST['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}

function addNewCollectionElements($collectionID) {
    for ($i=0; $i < $_POST['numElems']; $i++) {
        $newCollectionElementID = $GLOBALS['dbh']->addCollectionElement($collectionID);
        switch ($_POST['elemType']) {
            case 'bibliografia':
                if (checkIsSet(['citazione'.$i, 'HREF'.$i, 'DOI'.$i])) {
                    $href = $_POST['HREF'.$i] != "" ? $_POST['HREF'.$i] : null;
                    $doi = $_POST['DOI'.$i] != "" ? $_POST['DOI'.$i] : null;
                    $GLOBALS['dbh']->addBibliographyElement($_POST['citazione'.$i], $newCollectionElementID, $collectionID, $href, $doi);
                }
                break;
            case 'cronologia':
                if (checkIsSet(['data'.$i, 'localita'.$i, 'descrizione'.$i])) {
                    $GLOBALS['dbh']->addChronologyElement($_POST['data'.$i], $newCollectionElementID, $collectionID, $_POST['localita'.$i], $_POST['descrizione'.$i]);
                }
                break;
            case 'emeroteca':
                if (checkIsSet(['giornale'.$i, 'titolo'.$i, 'dataPubblicazione'.$i])) {
                    $href = $_POST['HREF'.$i] != "" ? $_POST['HREF'.$i] : null;
                    $GLOBALS['dbh']->addNewsPaperLibraryElement($_POST['giornale'.$i], $_POST['dataPubblicazione'.$i], $newCollectionElementID, $collectionID, $_POST['titolo'.$i], $href);
                }
                break;
            case 'fototeca':
                if (checkIsSet(['descrizione'.$i])) {
                    $GLOBALS['dbh']->addPhotoLibraryElement($_POST['descrizione'.$i], $newCollectionElementID, $collectionID);
                }
                break;
            case 'rete':
                if (checkIsSet(['tipologia'.$i, 'titolo'.$i, 'fonte'.$i, 'HREF'.$i, 'DOI'.$i])) {
                    $source = $_POST['fonte'.$i] != "" ? $_POST['fonte'.$i] : null;
                    $doi = $_POST['DOI'.$i] != "" ? $_POST['DOI'.$i] : null;
                    $GLOBALS['dbh']->addNetworkResource($_POST['tipologia'.$i], $newCollectionElementID, $collectionID, $_POST['titolo'.$i], $_POST['HREF'.$i], $source, $doi);
                }
                break;
        }
    }
}
?>