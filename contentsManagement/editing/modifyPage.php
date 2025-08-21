<?php
/**
 * Pagina per modifica di un contenuto di tipo "Pagina".
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Pagina";
$templateParams["nome"] = TEMPLATE_PATH . "pageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editPage.php";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "tinymce.js", JS_PATH . "showNewIndexItemFields.js", JS_PATH . "showNewNoteFields.js", JS_PATH . "showNewResourceCollectionFields.js", JS_PATH . "showInvItemsQuantityInputs.js", JS_PATH . "showCurrentIndexItemsAndNotes.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_PATH . "style.css";

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
        $templateParams["js"][] = JS_PATH . "showCurrentResourceCollections.js";
    }
}

require BASE_TEMPLATE_PATH;
?>