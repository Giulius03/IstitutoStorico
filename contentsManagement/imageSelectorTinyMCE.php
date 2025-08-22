<?php
/**
 * Pagina per selezione di immagini tramite TinyMCE.
 */
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Seleziona Immagine";
$templateParams["nome"] = "selectImgTinyMCE.php";
$templateParams["js"] = array(JS_URL."tinymce.js");
$templateParams["css"] = CSS_URL."style.css";

require TEMPLATE_PATH . $templateParams["nome"];
?>