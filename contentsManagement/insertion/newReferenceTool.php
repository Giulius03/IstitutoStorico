<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuovo Strumento di Corredo";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "strumento di corredo";

require '../../template/base.php';
?>