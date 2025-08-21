<?php
/**
 * Script per eseguire il logout di un amministratore.
 */
require_once '/bootstrap.php';

$status["successful"] = false;

unset($_SESSION["email"]);
if (!isset($_SESSION["email"])) {
    $status["successful"] = true;
}

header('Content-Type: application/json');
echo json_encode($status);
?>