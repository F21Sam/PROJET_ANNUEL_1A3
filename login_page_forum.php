<?php
error_reporting(E_ALL); 
ini_set('display_errors',1);
session_start();
include_once('includes/dbpa.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'TESTEMAIL/phpmailer/src/Exception.php';
require 'TESTEMAIL/phpmailer/src/PHPMailer.php';
require 'TESTEMAIL/phpmailer/src/SMTP.php';

if (isset($_POST['password']) && isset($_POST['email'])) {
    $sql = 'SELECT id_user, pseudo, nom, mail, password, date_ins, role_type, confirm, newsletter, banned FROM USER WHERE mail = :email';

    $preparedSql = $dbh->prepare($sql);
    $preparedSql->execute(['email' => $_POST['email']]);

    $user = $preparedSql->fetch();
    $email = $_POST['email'];

    if ($user) {
        if (password_verify($_POST['password'], $user['password'])) {
            if ($user['confirm'] == 1) {
                $dateLogin = 'UPDATE USER SET login_date = NOW() WHERE id_user = :id_user';
                $preparedSqlDate = $dbh->prepare($dateLogin);
                $preparedSqlDate->execute(['id_user' => $user['id_user']]);

                if ($user['banned'] == 1) {
                    $_SESSION['message'] = "";
                    header("Location: banni.php");
                    exit();
                } else {
                    $_SESSION['id_user'] = $user['id_user'];
                    $_SESSION['pseudo'] = $user['pseudo'];
                    header("Location: forum_affichage_page.php");
                    exit();
                }
            } else {
                $erreur_confirm = "Votre adresse mail n'est pas confirmée.";

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
                    $mail->addAddress($email);
                    $mail->isHTML(true);
                    $mail->Subject = "Confirmation d'inscription chez Amimal";
                    $mail->Body = "<a href='https://amimal.freeddns.org/includes/verif_insc.php?num=" . $_SESSION['id_user'] . "'>Cliquez ici pour confirmer votre compte.</a>";

                    $mail->send();

                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                }
            }
        } else {
            echo "Mot de passe incorrect.";
        }
    } else {
        echo "Cet utilisateur n'existe pas.";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Login Page</title>
	<!-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE//style_2.css" rel="stylesheet" />
<body class="class-login">
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card_login">
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
                        <a href="index.php">
						    <img src="IMAGES/logo-Amimal.png" class="brand_logo " alt="Logo">
                        </a>
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="" method="POST">
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user">
                                    <svg src="IMAGES/person-fill.svg" width="26" height="26" fill="white" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                      </svg>
                                </i></span>
							</div>
							<input id="email" type="email" class="form-control input_pass" placeholder="Votre Email" name="email">						</div>
						<div class="input-group mb-2">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i>
                                    <svg src="IMAGES/key-fill.svg" width="26" height="26" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                      </svg>
                                </span>
							</div>
							<input type="password" name="password" class="form-control input_pass" value="" placeholder="Votre mot de masse" autocomplete="off">
                            
						</div>
                        <p class= text-white style="font-size: 12px;">Connectez vous pour accéder au forum !</p>
						<span><?php if(isset($erreur_confirm)) echo '<span style="color: red; font-size: 12px;">' . $erreur_confirm . '</span>'?></span>
							<div class="d-flex justify-content-center mt-3 login_container">
							<button type="submit" name="connect" class="btn btn-primary mx-lg-2 btn-orange">Se Connecter <svg src="IMAGES/arrow-right-circle.svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
						  </svg>
						</button>
				   		</div>
					</form>
				</div>
				<div class="mt-4">
				<div class="d-flex justify-content-center links text-white">
					<p>Besoin de créer un compte ?</p>
					
					<a href="inscription.php" class="ml-2 ">S'inscrire</a>
				</div>
				<div class="d-flex justify-content-center links">
					<a href="#">Mot de passe oublié</a>
				</div>
			</div>
		</div>
	</div>
</div>

