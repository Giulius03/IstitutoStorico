<?php
/**
 * Pagina di amministrazione dei contenuti del sito.
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
$templateParams["titolo"] = "Gestione Contenuti";
$templateParams["nome"] = TEMPLATE_PATH . "adminTemplate.php";
$templateParams["js"] = array(JS_URL."showContents.js", JS_URL."logout.js", JS_URL."tinymce.js");
$templateParams["css"] = CSS_URL . "style.css";

require BASE_TEMPLATE_PATH;
?>