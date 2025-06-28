<?php
require_once 'bootstrap.php';

//Base Template
$templateParams["nome"] = "pageTemplate.php";
$templateParams["css"] = "css/style.css";
$templateParams["js"] = array("js/base.js");
if (isset($_GET['id'])) {
    $templateParams["page"] = $dbh->getPageFromID($_GET['id']);
    $templateParams["titolo"] = $templateParams["page"]["title"];
}

require 'template/base.php';
?>