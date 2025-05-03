<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Tag";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deleteTag.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "tag";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getTagFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require '../../template/base.php';
?>