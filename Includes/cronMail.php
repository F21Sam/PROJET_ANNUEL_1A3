<?php 
error_reporting(E_ALL); 
ini_set('display_errors', 1);
global $dbh;
require_once('dbpa.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../TESTEMAIL/phpmailer/src/Exception.php';
require '../TESTEMAIL/phpmailer/src/PHPMailer.php';
require '../TESTEMAIL/phpmailer/src/SMTP.php'; 

// Requête SQL pour sélectionner les utilisateurs dont la date de connexion est plus de 30 jours
// et qui n'ont pas reçu de notification depuis plus de 7 jours.
$sql = 'SELECT id_user, mail FROM USER WHERE login_date <= NOW() - INTERVAL 30 DAY AND (notification_date IS NULL OR notification_date <= NOW() - INTERVAL 7 DAY)';
$stmt = $dbh->prepare($sql);
$stmt = $dbh->query($sql);
$adresses_email = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($adresses_email) > 0) {
    $mail = new PHPMailer(true);
    foreach ($adresses_email as $email) {
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'amimal.refuge@gmail.com';
            $mail->Password = 'gxuz vcbd zkod imle';  // Attention à la gestion des mots de passe sensibles
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;

            $mail->setFrom('amimal.refuge@gmail.com');
            $mail->addAddress($email['mail']);

            $mail->isHTML(true);
            $mail->Subject = "Cela fait un moment qu'on vous a pas vu";
            $mail->Body = "Revenez svp !";

            $mail->send();
            echo "Mail envoyé à: " . $email['mail'] . "\n";
            
            // Mise à jour de la colonne notification_date après l'envoi du mail
            $updateSql = 'UPDATE USER SET notification_date = NOW() WHERE id_user = :id_user';
            $updateStmt = $dbh->prepare($updateSql);
            $updateStmt->execute([':id_user' => $email['id_user']]);
            echo "notification_date mis à jour pour: " . $email['mail'] . "\n";

        } catch (Exception $e) {
            echo "Le message n'a pas pu être envoyé. Erreur de Mailer: {$mail->ErrorInfo}\n";
        }
    }
} else {
    echo "Aucun email à envoyer.\n";
}
