<?php
require_once '../../bootstrap.php';

$numOfTags = $dbh->getNumOfTags();
$tagsSet = [];
for ($i=1; $i <= $numOfTags; $i++) { 
    if (isset($_POST['tag'.$i])) {
        $tagsSet[] = (int) $_POST['tag'.$i];
    }
}

$fieldsName = ['pageType', 'titolo', 'slug', 'visible', 'content', 'titoloSEO', 'testoSEO', 'chiaviSEO'];
if (checkIsSet($fieldsName)) {
    try {
        $idNewPage = $dbh->addPage($_POST['titolo'], $_POST['slug'], $_POST['content'], isset($_POST['visible']) ? true : false, $_POST['titoloSEO'], $_POST['testoSEO'], $_POST['chiaviSEO']);
        $dbh->connectTagsToPage($idNewPage, $tagsSet);
        header('Location: ../admin.php?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>