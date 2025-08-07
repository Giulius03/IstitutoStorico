<?php
require_once '../bootstrap.php';

// Percorso assoluto dove salvare l'immagine
$uploadDir = realpath(__DIR__ . '/../sites/default/images') . '/';
$targetFile = $uploadDir . basename($_FILES['file']['name']);
$response = [];

if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
    // Percorso relativo per il browser
    $webPath = '/sites/default/images/' . basename($targetFile);
    $response['location'] = $webPath;
} else {
    http_response_code(500);
    $response['error'] = "Errore nel caricamento dell'immagine.";
}

header('Content-Type: application/json');
echo json_encode($response);