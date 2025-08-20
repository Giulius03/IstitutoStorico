<?php
/**
 * Pagina di login come amministratore.
 */
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Login";
$templateParams["nome"] = "loginTemplate.php";
$templateParams["js"] = array("js/login.js", "js/base.js");
$templateParams["css"] = "css/style.css";

require 'template/base.php';
?>