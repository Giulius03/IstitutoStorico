<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuovo Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = "contentAdders/addNewInvItem.php";
$templateParams["css"] = "../../css/style.css";
$templateParams["js"] = array("../../js/logout.js");
$templateParams["noPageType"] = "articolo d'inventario";

require '../../template/base.php';
?>