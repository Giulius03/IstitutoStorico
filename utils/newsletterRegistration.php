<?php
$dir = __DIR__;
while (!file_exists($dir . '/bootstrap.php')) {
    $parent = dirname($dir);
    if ($parent === $dir) {
        die('bootstrap.php non trovato!');
    }
    $dir = $parent;
}
require_once $dir . '/bootstrap.php';

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