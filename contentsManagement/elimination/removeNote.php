<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Nota";
$templateParams["nome"] = "../../template/pageComponents.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteNote.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["componentName"] = "nota";

if (isset($_GET['id'])) {
    $templateParams["component"] = $dbh->getNoteFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>