<?php
/**
 * Pagina di login come amministratore.
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
$templateParams["titolo"] = "Login";
$templateParams["nome"] = TEMPLATE_PATH . "loginTemplate.php";
$templateParams["js"] = array(JS_URL."login.js", JS_URL."base.js");
$templateParams["css"] = CSS_URL."style.css";

require BASE_TEMPLATE_PATH;
?>