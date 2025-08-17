<?php
require_once '../bootstrap.php';
require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (checkIsSet(['object', 'body'])) {
    try {
        $oggetto = $_POST['object'];
        $contenuto = $_POST['body'];

        $template = file_get_contents('../template/newsletter_template.html');

        $html = str_replace('{{CONTENUTO_MAIL}}', $contenuto, $template);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'newsletter.istorecofc@gmail.com';
        // password indirizzo gmail: IstitutoStoricoFC2025
        // email di recupero attuale: marco.gi2003@gmail.com
        // numero di telefono attuale per verifica in due passaggi: +39 391 305 8063 (necessario per utilizzare le password per app)

        // Password per app "SitoIstitutoStorico"
        $mail->Password = 'ynyr wsvm gydu tnao';

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->isHTML(true);
        $mail->Subject = $oggetto;
        $mail->Body = $html;

        $subscribers = $dbh->getNewsletterSubscribers();
        $lastSub = "";
        foreach ($subscribers as $sub) {
            $lastSub = $sub;
            $mail->clearAddresses();
            $mail->addAddress($sub['email']);
            $mail->send();
        }
        header('Location: ../admin.php');
    } catch (Exception $e) {
        echo "Errore nell'invio a {$lastSub['email']}: {$mail->ErrorInfo}<br>";
    }
}
?>