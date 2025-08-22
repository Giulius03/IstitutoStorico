<?php
/**
 * Pagina per inserimento di un articolo d'inventario.
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
$templateParams["titolo"] = "Nuovo Articolo d'Inventario";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_PATH . "addNewInvItem.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH."logout.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "articolo d'inventario";

require BASE_TEMPLATE_PATH;
?>