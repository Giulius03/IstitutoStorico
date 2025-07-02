<?php
$secret = "6LerU3UrAAAAAD3bCJ3r8Ggv5TCn4RxDFkiDJzMH";
$response = $_POST['g-recaptcha-response'];

$verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$response");
$captcha_success = json_decode($verify);

if ($captcha_success->success) {
    echo "Verifica riuscita. Sei umano!";
} else {
    echo "Verifica fallita. Riprova.";
}
?>