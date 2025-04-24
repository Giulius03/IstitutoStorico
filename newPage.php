<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuova Pagina";
$templateParams["nome"] = "pageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["js"] = array("js/tinymce.js");

require 'template/base.php';
?>