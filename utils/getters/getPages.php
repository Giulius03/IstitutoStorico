<?php
require_once '../../bootstrap.php';

$pages = $dbh->getPages();

header('Content-Type: application/json');
echo json_encode($pages);
?>