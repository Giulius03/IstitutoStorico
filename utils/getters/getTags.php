<?php
require_once '../../bootstrap.php';

$tags = $dbh->getTags();

header('Content-Type: application/json');
echo json_encode($tags);
?>