<?php
error_reporting(E_ALL); 
ini_set('display_errors', 1);
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include_once('../journalisations.php');
include_once('../header.php');

$pdo = new PDO('mysql:host=54.37.65.182;dbname=projet', 'distant', 'secure');

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if (isset($_POST["send"])) {
    $sql = "SELECT mail FROM USER WHERE newsletter = 1";
    $stmt = $pdo->query($sql);
    $adresses_email = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (count($adresses_email) > 0) {
        $mail = new PHPMailer(true);
        foreach ($adresses_email as $email) {
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'amimal.refuge@gmail.com';
                $mail->Password = 'gxuz vcbd zkod imle';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
                $mail->Port = 465;

                $mail->setFrom('amimal.refuge@gmail.com');
                $mail->addAddress($email['mail']);

                $mail->isHTML(true);

                $mail->Subject = $_POST["subject"];
                $mail->Body = $_POST["message"];

                $mail->send();
            } catch (Exception $e) {
                echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
        }
    }
    
    // Enregistrement de l'historique de la newsletter
    $subject = $_POST["subject"];
    $content = $_POST["message"];
    $sql = "INSERT INTO NewsletterHistory (subject, content) VALUES (:subject, :content)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['subject' => $subject, 'content' => $content]);
}
?>