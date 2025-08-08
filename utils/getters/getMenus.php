<?php
require_once '../../bootstrap.php';

if (isset($_GET['ordBy'])) {
    $menus = $dbh->getMenus($_GET['ordBy']);
}
header('Content-Type: application/json');
echo json_encode($menus);
?>