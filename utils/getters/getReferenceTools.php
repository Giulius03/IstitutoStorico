<?php
require_once '../../bootstrap.php';

$referenceTools = $dbh->getReferenceTools();

header('Content-Type: application/json');
echo json_encode($referenceTools);
?>