<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuovo Tag";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = "contentAdders/addNewTag.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "tag";

require '../../template/base.php';
?>