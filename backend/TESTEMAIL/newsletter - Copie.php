<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

if(isset($_POST["send"])){
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'amimal.refuge@gmail.com';
        $mail->Password = 'gxuz vcbd zkod imle';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
        $mail->Port = 465;

        $mail->setFrom('amimal.refuge@gmail.com');

        $mail->addAddress('qdelneuf@gmail.com');

        $mail->isHTML(true);

        $mail->Subject = ($_POST["subject"]);
        $mail->Body = ($_POST["message"]);

        $mail->send();

 //       echo "
  //      <script>
  //      alert('Sent successfully');
  //      document.location.href = 'index.php';
  //      </script>
   //     ";
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
}
?>