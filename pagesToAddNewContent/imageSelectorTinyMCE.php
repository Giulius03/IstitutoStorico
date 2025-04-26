<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Seleziona Immagine";
$templateParams["nome"] = "selectImgTinyMCE.php";
$templateParams["js"] = array("js/tinymce.js");

require 'template/base.php';
?>