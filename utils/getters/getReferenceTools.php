<?php
require_once '../../bootstrap.php';

if (isset($_GET['ordBy'])) {
    $referenceTools = $dbh->getReferenceTools($_GET['ordBy']);
}
header('Content-Type: application/json');
echo json_encode($referenceTools);
?>