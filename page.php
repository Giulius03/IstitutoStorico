<?php
/**
 * Pagina che visualizza il contenuto di tutte quelle presenti nel sito.
 */
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

//Base Template
$templateParams["nome"] = TEMPLATE_PATH . "pageTemplate.php";
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["js"] = array(JS_URL . "base.js");
if (isset($_GET['slug'])) {
    $templateParams["page"] = $dbh->getPageFromSlug($_GET['slug']);
    $templateParams["titolo"] = count($templateParams["page"]) > 0 ? $templateParams["page"][0]["title"] : "Pagina non trovata";
}

require BASE_TEMPLATE_PATH;
?>