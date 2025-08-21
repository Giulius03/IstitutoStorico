<?php
/**
 * Pagina di modifica per una voce del menù.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Voce del Menù";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editMenuItem.php";
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["js"] = array(JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "voce del menù";

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    $templateParams["content"] = $dbh->getMenuItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idMenu=".$_GET['idMenu'];
    $templateParams["otherItems"] = $dbh->getMenuItems($_GET['idMenu']);
}

require BASE_TEMPLATE_PATH;
?>