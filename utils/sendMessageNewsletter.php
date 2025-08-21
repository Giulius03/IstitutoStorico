<?php
/**
 * Script per invio tramite GMail di notifiche agli utenti iscritti alla newsletter.
 */
require_once '/bootstrap.php';
require VENDOR_FOULDER_PATH . 'autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (checkIsSet(['object', 'body'])) {
    try {
        $object = $_POST['object'];
        $content = $_POST['body'];

        $template = file_get_contents(TEMPLATE_PATH . 'newsletter_template.html');

        $html = str_replace('{{CONTENUTO_MAIL}}', $content, $template);

        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USERNAME');
        // password indirizzo gmail: IstitutoStoricoFC2025
        // email di recupero attuale: marco.gi2003@gmail.com
        // numero di telefono attuale per verifica in due passaggi: +39 391 305 8063 (necessario per utilizzare le password per app)

        // Password per app "SitoIstitutoStorico"
        $mail->Password = getenv('SMTP_PASSWORD');

        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->isHTML(true);
        $mail->Subject = $object;
        $mail->Body = $html;

        $subscribers = $dbh->getNewsletterSubscribers();
        $lastSub = "";
        foreach ($subscribers as $sub) {
            $lastSub = $sub;
            $mail->clearAddresses();
            $mail->addAddress($sub['email']);
            $mail->send();
        }
        header('Location: ' . ADMIN_PAGE_PATH);
    } catch (Exception $e) {
        echo "Errore nell'invio a {$lastSub['email']}: {$mail->ErrorInfo}<br>";
    }
}
?>