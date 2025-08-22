<?php
/**
 * Pagina per inserimento di uno strumento di corredo.
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
$templateParams["titolo"] = "Nuovo Strumento di Corredo";
$templateParams["nome"] = TEMPLATE_PATH."noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_PATH."addNewRefTool.php";
$templateParams["css"] = CSS_PATH."style.css";
$templateParams["js"] = array(JS_PATH."logout.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "strumento di corredo";

require BASE_TEMPLATE_PATH;
?>