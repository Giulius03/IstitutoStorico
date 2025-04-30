<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuovo Articolo d'Inventario";
$templateParams["nome"] = "../../template/noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["css"] = "../../css/style.css";
$templateParams["noPageType"] = "articolo d'inventario";

require '../../template/base.php';
?>