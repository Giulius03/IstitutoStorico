<?php
/**
 * Pagina per inserimento di un articolo d'inventario.
 */
require_once '/bootstrap.php';

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