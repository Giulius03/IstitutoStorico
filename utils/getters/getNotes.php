<?php
require_once '/bootstrap.php';

if (isset($_GET['id'])) {
    $notes = $dbh->getNotesFromPageID($_GET['id']);
}

header('Content-Type: application/json');
echo json_encode($notes);
?>