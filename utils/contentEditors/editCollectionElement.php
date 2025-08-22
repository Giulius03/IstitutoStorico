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

if (isset($_GET['id']) && isset($_GET['type'])) {
    try {
        switch ($_GET['type']) {
            case "bibliografia":
                if (checkIsSet(['cit', 'href', 'doi'])) {
                    $href = $_POST['href'] != "" ? $_POST['href'] : null;
                    $doi = $_POST['doi'] != "" ? $_POST['doi'] : null;
                    $dbh->updateBibliographyElement($_GET['id'], $_POST['cit'], $href, $doi);
                }
                break;
            case "cronologia":
                if (checkIsSet(['localita', 'data', 'descrizione'])) {
                    $dbh->updateChronologyElement($_GET['id'], $_POST['data'], $_POST['localita'], $_POST['descrizione']);
                }
                break;
            case "emeroteca":
                if (checkIsSet(['giornale', 'titolo', 'data', 'href'])) {
                    $href = $_POST['href'] != "" ? $_POST['href'] : null;
                    $GLOBALS['dbh']->updateNewsPaperLibraryElement($_GET['id'], $_POST['giornale'], $_POST['data'], $_POST['href'], $_POST['titolo']);
                }
                break;
            case "fototeca":
                if (checkIsSet(['descrizione'])) {
                    $dbh->updatePhotoLibraryElement($_GET['id'], $_POST['descrizione']);
                }
                break;
            case "rete":
                if (checkIsSet(['tipo', 'fonte', 'titolo', 'href', 'doi'])) {
                    $href = $_POST['href'] != "" ? $_POST['href'] : null;
                    $source = $_POST['fonte'] != "" ? $_POST['fonte'] : null;
                    $doi = $_POST['doi'] != "" ? $_POST['doi'] : null;
                    $GLOBALS['dbh']->updateNetworkResource($_GET['id'], $_POST['tipo'], $_POST['titolo'], $href, $source, $doi);
                }
                break;
        }
        header('Location: ' . CONTENTS_EDITING_URL . 'modifyResourceCollection.php?id='.$_GET['idCollection'].'&idPage='.$_GET['idPage']);
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>