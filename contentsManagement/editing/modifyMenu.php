<?php
/**
 * Pagina per modifica di un menù.
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
$templateParams["titolo"] = "Modifica Menù";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_URL . "editMenu.php";
$templateParams["js"] = array(JS_URL . "showNewMenuItemFields.js", JS_URL . "showCurrentMenuItems.js", JS_URL . "logout.js", JS_URL . "contentsManagementNavbarLinks.js");
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["noPageType"] = "menù";

if (isset($_GET['id'])) {
    $templateParams["content"] = $dbh->getMenuFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id'];
}

require BASE_TEMPLATE_PATH;
?>