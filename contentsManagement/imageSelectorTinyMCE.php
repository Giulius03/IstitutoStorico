<?php
/**
 * Pagina per selezione di immagini tramite TinyMCE.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Seleziona Immagine";
$templateParams["nome"] = "selectImgTinyMCE.php";
$templateParams["js"] = array("../js/tinymce.js");
$templateParams["css"] = "../css/style.css";

require TEMPLATE_PATH . $templateParams["nome"];
?>