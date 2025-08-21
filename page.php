<?php
/**
 * Pagina che visualizza il contenuto di tutte quelle presenti nel sito.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["nome"] = TEMPLATE_PATH . "pageTemplate.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "base.js");
if (isset($_GET['slug'])) {
    $templateParams["page"] = $dbh->getPageFromSlug($_GET['slug']);
    $templateParams["titolo"] = count($templateParams["page"]) > 0 ? $templateParams["page"][0]["title"] : "Pagina non trovata";
}

require BASE_TEMPLATE_PATH;
?>