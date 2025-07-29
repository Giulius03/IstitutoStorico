<?php
require_once '../bootstrap.php';

$status["message"] = "";

if (checkIsSet(['nameSurname', 'email'])) {
    try {
        $newSubscriberID = $dbh->addToTheNewsletter($_POST['nameSurname'], $_POST['email']);
        $status["message"] = "Registrazione effettuata con successo.";
    } catch (Exception $e) {
        $status["message"] = "C'è stato un errore:\n" . $e->getMessage();
    }
}

header('Content-Type: application/json');
echo json_encode($status);
?>