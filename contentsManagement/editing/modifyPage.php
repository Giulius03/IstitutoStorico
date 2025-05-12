<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Pagina";
$templateParams["nome"] = "../../template/pageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = "contentEditors/editPage.php";
$templateParams["js"] = array("../../js/tinymce.js", "../../js/showNewIndexItemFields.js", "../../js/showNewNoteFields.js", "../../js/showNewCollectionElemFields.js", "../../js/showInvItemsQuantityInputs.js", "../../js/showCurrentIndexItemsAndNotes.js");
$templateParams["css"] = "../../css/style.css";

if (isset($_GET['id'])) {
    $templateParams["page"] = $dbh->getPageFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
    $templateParams["pageTags"] = $dbh->getTagsFromPageID($_GET['id']);
    $templateParams["containedPagesTags"] = $dbh->getContainedPagesTagsFromContainerID($_GET['id']);
    if ($templateParams["page"]["type"] == "Pagina di Archivio") {
        $templateParams["actionFile"] .= "?type=".$templateParams["page"]["type"];
        $templateParams["archivePage"] = $dbh->getArchivePageFromPageID($_GET['id']);
        $templateParams["referenceTools"] = $dbh->getReferenceToolsFromArchivePageID($_GET['id']);
        $templateParams["inventoryItems"] = $dbh->getInventoryItemsFromArchivePageID($_GET['id']);
    } else if ($templateParams["page"]["type"] == "Raccolta di Risorse") {
        $templateParams["actionFile"] .= "?type=".$templateParams["page"]["type"];
        $templateParams["resourceCollection"] = $dbh->getResourceCollectionFromPageID($_GET['id']);
        $templateParams["js"][] = "../../js/ShowCurrentCollectionElements.js";
    }
}

require '../../template/base.php';
?>