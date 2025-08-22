<?php
/**
 * Script per eseguire il logout di un amministratore.
 */
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

$status["successful"] = false;

unset($_SESSION["email"]);
if (!isset($_SESSION["email"])) {
    $status["successful"] = true;
}

header('Content-Type: application/json');
echo json_encode($status);
?>