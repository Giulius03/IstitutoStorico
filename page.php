<?php
/**
 * Pagina che visualizza il contenuto di tutte quelle presenti nel sito.
 */
require_once 'bootstrap.php';

//Base Template
$templateParams["nome"] = "pageTemplate.php";
$templateParams["css"] = "css/style.css";
$templateParams["js"] = array("js/base.js");
if (isset($_GET['slug'])) {
    $templateParams["page"] = $dbh->getPageFromSlug($_GET['slug']);
    $templateParams["titolo"] = count($templateParams["page"]) > 0 ? $templateParams["page"][0]["title"] : "Pagina non trovata";
}

require 'template/base.php';
?>