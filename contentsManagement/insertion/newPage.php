<?php
/**
 * Pagina per inserimento di un contenuto di tipo "Pagina".
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
$templateParams["titolo"] = "Nuova Pagina";
$templateParams["nome"] = TEMPLATE_PATH . "pageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_URL . "addNewPage.php";
$templateParams["js"] = array(JS_URL."logout.js", JS_URL."tinymce.js", JS_URL."showSpecifics.js", JS_URL."showNewIndexItemFields.js", JS_URL."showNewNoteFields.js", JS_URL."showInvItemsQuantityInputs.js", JS_URL."showNewResourceCollectionFields.js", JS_URL."contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_URL."style.css";

require BASE_TEMPLATE_PATH;
?>