<?php
/**
 * Pagina home del sito per visualizzazione utenti.
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
$templateParams["titolo"] = "Istituto storico della Resistenza e dell'Età contemporanea di Forlì-Cesena";
$templateParams["nome"] = TEMPLATE_PATH . "home.php";
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["js"] = array(JS_URL."base.js");

require BASE_TEMPLATE_PATH;
?>