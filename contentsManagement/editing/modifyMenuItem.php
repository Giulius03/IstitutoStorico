<?php
/**
 * Pagina di modifica per una voce del menù.
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
$templateParams["titolo"] = "Modifica Voce del Menù";
$templateParams["nome"] = TEMPLATE_PATH . "noPageFormTemplate.php";
$templateParams["action"] = "E";
$templateParams["actionFile"] = CONTENT_EDITORS_SCRIPT_URL . "editMenuItem.php";
$templateParams["css"] = CSS_URL . "style.css";
$templateParams["js"] = array(JS_URL . "logout.js", JS_URL . "contentsManagementNavbarLinks.js");
$templateParams["noPageType"] = "voce del menù";

if (isset($_GET['id']) && isset($_GET['idMenu'])) {
    $templateParams["content"] = $dbh->getMenuItemFromID($_GET['id']);
    $templateParams["actionFile"] .= "?id=".$_GET['id']."&idMenu=".$_GET['idMenu'];
    $templateParams["otherItems"] = $dbh->getMenuItems($_GET['idMenu']);
}

require BASE_TEMPLATE_PATH;
?>