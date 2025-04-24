<?php
require_once '../bootstrap.php';

$targetFile = "../upload/" . basename($_FILES['file']['name']);
$response = [];

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
    $response['location'] = $targetFile; // Percorso immagine
} else {
    http_response_code(500);
    $response['error'] = "Errore nel caricamento dell'immagine.";
}

header('Content-Type: application/json');
echo json_encode($response);
?>