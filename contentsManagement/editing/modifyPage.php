<?php
/**
 * Pagina per modifica di un contenuto di tipo "Pagina".
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
$templateParams["titolo"] = "Modifica Pagina";
$templateParams["nome"] = TEMPLATE_PATH . "pageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editPage.php";
$templateParams["js"] = array(JS_URL . "logout.js", JS_URL . "tinymce.js", JS_URL . "showNewIndexItemFields.js", JS_URL . "showNewNoteFields.js", JS_URL . "showNewResourceCollectionFields.js", JS_URL . "showInvItemsQuantityInputs.js", JS_URL . "showCurrentIndexItemsAndNotes.js", JS_URL . "contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_URL . "style.css";

if (isset($_GET['id'])) {
    $templateParams["page"] = $dbh->getPageFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
    $templateParams["pageTags"] = $dbh->getTagsFromPageID($_GET['id']);
    $templateParams["containedPagesTags"] = $dbh->getContainedPagesTagsFromContainerID($_GET['id']);
    if ($templateParams["page"]["type"] == "Pagina di Archivio") {
        $templateParams["actionFile"] .= "&type=archivio";
        $templateParams["archivePage"] = $dbh->getArchivePageFromPageID($_GET['id']);
        $templateParams["referenceTools"] = $dbh->getReferenceToolsFromArchivePageID($_GET['id']);
        $templateParams["inventoryItems"] = $dbh->getInventoryItemsFromArchivePageID($_GET['id']);
    } else if ($templateParams["page"]["type"] == "Raccolta di Risorse") {
        $templateParams["actionFile"] .= "&type=raccolta";
        $templateParams["js"][] = JS_URL . "showCurrentResourceCollections.js";
    }
}

require BASE_TEMPLATE_PATH;
?>