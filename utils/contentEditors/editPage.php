<?php
require_once '../../bootstrap.php';

$fieldsName = ['titolo', 'slug', 'autore', 'content', 'titoloSEO', 'testoSEO', 'chiaviSEO', 'numVoci', 'numNote'];

if (checkIsSet($fieldsName) && isset($_GET['id'])) {
    try {
        $dbh->updatePage($_GET['id'], $_POST['titolo'], $_POST['slug'], $_POST['content'], isset($_POST['visible']) ? true : false, $_POST['titoloSEO'], $_POST['testoSEO'], $_POST['chiaviSEO'], $_POST['autore']);
        header('Location: ../../admin.php?cont=Pagine');
    } catch (Exception $e) {
        echo "Errore: " . $e->getMessage();
    }
}
?>