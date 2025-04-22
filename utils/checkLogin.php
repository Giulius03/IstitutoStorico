<?php
require_once '../bootstrap.php';

$status["successful"] = false;
$status["error"] = "";
$status["admin"] = false;

if (isset($_POST["username"]) && isset($_POST["password"])) {
    $login_result = $dbh->checkLogin($_POST["username"]);
    if (count($login_result) == 0) {
        $status["error"] = "Questo account non esiste.";
    } else if ($_POST["password"] != $login_result[0]["password"]) {
        $status["error"] = "Password errata.";
    } 
    // da implementare la registrazione utente con password hash
    // else if (!password_verify($_POST["password"], $login_result[0]["password"])) {
    //     $status["error"] = "Password errata.";
    else {
        $status["successful"] = true;
        $status["admin"] = $login_result[0]["adminYN"] == 1 ? true : false;
        $_SESSION["email"] = $login_result[0]["userEmail"];
        $_SESSION["isAdmin"] = $status["admin"];
    }
}

header('Content-Type: application/json');
echo json_encode($status);
?>