<?php
/**
 * Pagina di amministrazione dei contenuti del sito.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Gestione Contenuti";
$templateParams["nome"] = TEMPLATE_PATH . "adminTemplate.php";
$templateParams["js"] = array(JS_PATH."showContents.js", JS_PATH."logout.js", JS_PATH."tinymce.js");
$templateParams["css"] = CSS_PATH . "style.css";

require BASE_TEMPLATE_PATH;
?>