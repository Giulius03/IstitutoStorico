<?php
/**
 * Pagina per modifica di un menù.
 */
require_once '/bootstrap.php';

//Base Template
$templateParams["titolo"] = "Modifica Menù";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_PATH . "editMenu.php";
$templateParams["js"] = array(JS_PATH . "showNewMenuItemFields.js", JS_PATH . "showCurrentMenuItems.js", JS_PATH . "logout.js", JS_PATH . "contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_PATH . "style.css";
$templateParams["noPageType"] = "menù";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getMenuFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require BASE_TEMPLATE_PATH;
?>