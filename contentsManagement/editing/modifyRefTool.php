<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Strumento di Corredo";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editRefTool.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "Strumenti di corredo";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getReferenceToolFromID($_GET['id']);
}

require '../../template/base.php';
?>