<?php
require_once '../../bootstrap.php';

if (isset($_GET['cont']) && isset($_GET['string'])) {
    $contents = $dbh->getContentByName($_GET['cont'], $_GET['string']);
}

header('Content-Type: application/json');
echo json_encode($contents);
?>