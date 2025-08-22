<?php
/**
 * Pagina per inserimento di un menù.
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
$templateParams["titolo"] = "Nuovo Menù";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_PATH . "addNewMenu.php";
$templateParams["js"] = array(JS_PATH."showNewMenuItemFields.js", JS_PATH."logout.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_PATH."style.css";
$templateParams["noPageType"] = "menù";

require BASE_TEMPLATE_PATH;
?>