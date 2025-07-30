<?php
require_once '../../bootstrap.php';

//Base Template
$templateParams["titolo"] = "Nuova Pagina";
$templateParams["nome"] = "../../template/pageFormTemplate.php";
$templateParams["action"] = "I";
$templateParams["actionFile"] = "contentAdders/addNewPage.php";
$templateParams["js"] = array("../../js/logout.js", "../../js/tinymce.js", "../../js/showSpecifics.js", "../../js/showNewIndexItemFields.js", "../../js/showNewNoteFields.js", "../../js/showInvItemsQuantityInputs.js", "../../js/showNewResourceCollectionFields.js");
$templateParams["css"] = "../../css/style.css";

require '../../template/base.php';
?>