<?php
require_once '/bootstrap.php';

if (isset($_GET['ordBy'])) {
    $pages = $dbh->getPages($_GET['ordBy']);
}

header('Content-Type: application/json');
echo json_encode($pages);
?>