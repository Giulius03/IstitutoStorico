<?php
/**
 * Pagina per inserimento di un tag.
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
$templateParams["titolo"] = "Nuovo Tag";
$templateParams["nome"] = TEMPLATE_PATH."noPageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_PATH."addNewTag.php";
$templateParams["css"] = CSS_PATH."style.css";
$templateParams["js"] = array(JS_PATH."logout.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "tag";

require BASE_TEMPLATE_PATH;
?>