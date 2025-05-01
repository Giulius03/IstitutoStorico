<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Tag";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editTag.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "Tag";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getTagFromID($_GET['id']);
}

require '../../template/base.php';
?>