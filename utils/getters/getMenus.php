<?php
require_once '../../bootstrap.php';

$menus = $dbh->getMenus();

header('Content-Type: application/json');
echo json_encode($menus);
?>