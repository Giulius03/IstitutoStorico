<?php
/**
 * Pagina per inserimento di uno strumento di corredo.
 */
require_once '/bootstrap.php';

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