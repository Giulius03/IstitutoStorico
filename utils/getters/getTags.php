<?php
require_once '../../bootstrap.php';

if (isset($_GET['ordBy'])) {
    $tags = $dbh->getTags($_GET['ordBy']);
}
header('Content-Type: application/json');
echo json_encode($tags);
?>