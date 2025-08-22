<?php
/**
 * Script per verificare il login di un amministratore, compresa comunicazione dell'errore nel caso fosse necessaria.
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
$status["error"] = "";

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $login_result = $dbh->checkLogin($_POST["username"]);
    if (count($login_result) == 0) {
        $status["error"] = "Questo account non esiste.";
    } else if ($_POST["password"] != $login_result[0]["password"]) {
        $status["error"] = "Password errata.";
    } else {
        $status["successful"] = true;
        $_SESSION["email"] = $login_result[0]["adminEmail"];
    }
}

header('Content-Type: application/json');
echo json_encode($status);
?>