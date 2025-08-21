<?php
/**
 * Pagina per inserimento di un contenuto di tipo "Pagina".
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuova Pagina";
$templateParams["nome"] = TEMPLATE_PATH . "pageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = CONTENT_ADDERS_SCRIPT_PATH . "addNewPage.php";
$templateParams["js"] = array(JS_PATH."logout.js", JS_PATH."tinymce.js", JS_PATH."showSpecifics.js", JS_PATH."showNewIndexItemFields.js", JS_PATH."showNewNoteFields.js", JS_PATH."showInvItemsQuantityInputs.js", JS_PATH."showNewResourceCollectionFields.js", JS_PATH."contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_PATH."style.css";

require BASE_TEMPLATE_PATH;
?>