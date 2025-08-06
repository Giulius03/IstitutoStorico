<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Elimina Pagina";
$templateParams["nome"] = "../../template/pageFormTemplate.php";
$templateParams["action"] = "D";
$templateParams["actionFile"] = "contentRemovers/deletePage.php";
$templateParams["js"] = array("../../js/logout.js", "../../js/tinymce.js", "../../js/showInvItemsQuantityInputs.js", "../../js/showCurrentIndexItemsAndNotes.js", "../../js/contentsManagementNavbarLinks.js");
$templateParams["css"] = "../../css/style.css";

if (isset($_GET['id'])) {
    $templateParams["page"] = $dbh->getPageFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
    $templateParams["pageTags"] = $dbh->getTagsFromPageID($_GET['id']);
    $templateParams["containedPagesTags"] = $dbh->getContainedPagesTagsFromContainerID($_GET['id']);
    if ($templateParams["page"]["type"] == "Pagina di Archivio") {
        $templateParams["archivePage"] = $dbh->getArchivePageFromPageID($_GET['id']);
        $templateParams["referenceTools"] = $dbh->getReferenceToolsFromArchivePageID($_GET['id']);
        $templateParams["inventoryItems"] = $dbh->getInventoryItemsFromArchivePageID($_GET['id']);
    } else if ($templateParams["page"]["type"] == "Raccolta di Risorse") {
        $templateParams["js"][] = "../../js/showCurrentResourceCollections.js";
    }
}
$templateParams["modalText"] = "L'eliminazione di questa pagina sarà permanente e comporterà anche la cancellazione di tutte le note, di tutte le voci del suo indice, di tutti gli eventuali collegamenti con strumenti di corredo, articoli d'inventario, tag, pagine contenute e di tutte le eventuali raccolte di risorse inserite precedentemente (compresi i rispettivi elementi).";

require '../../template/base.php';
?>