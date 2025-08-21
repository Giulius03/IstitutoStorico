<?php
/**
 * Script per verificare il login di un amministratore, compresa comunicazione dell'errore nel caso fosse necessaria.
 */
require_once '/bootstrap.php';

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