<?php
require 'vendor/autoload.php'; // PHPMailer o altro

// 1. Prendi dati dal form
$oggetto = $_POST['object'];
$contenuto = $_POST['body'];

// 2. Carica il template
$template = file_get_contents('newsletter_template.html');

// 3. Inserisci il contenuto nell'HTML
$html = str_replace('{{CONTENUTO_MAIL}}', $contenuto, $template);

// 4. Configura e invia mail
$mail = new PHPMailer(true);
$mail->isHTML(true);
$mail->Subject = $oggetto;
$mail->Body    = $html;
$mail->addAddress('utente@example.com'); // qui puoi ciclare tutti gli iscritti
$mail->send();
?>