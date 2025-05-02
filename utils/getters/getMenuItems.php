<?php
require_once '../../bootstrap.php';

if (isset($_GET['id'])) {
    $menuItems = $dbh->getMenuItems($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($menuItems);
?>