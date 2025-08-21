<?php
/**
 * Pagina di login come amministratore.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Login";
$templateParams["nome"] = TEMPLATE_PATH . "loginTemplate.php";
$templateParams["js"] = array(JS_PATH."login.js", JS_PATH."base.js");
$templateParams["css"] = CSS_PATH."style.css";

require BASE_TEMPLATE_PATH;
?>