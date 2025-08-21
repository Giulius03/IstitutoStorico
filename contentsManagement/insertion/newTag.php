<?php
/**
 * Pagina per inserimento di un tag.
 */
require_once '/bootstrap.php';

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