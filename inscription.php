<?php
session_start();
global $dbh;
require_once('includes/dbpa.php');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'TESTEMAIL/phpmailer/src/Exception.php';
require 'TESTEMAIL/phpmailer/src/PHPMailer.php';
require 'TESTEMAIL/phpmailer/src/SMTP.php'; 

if(isset($_SESSION['pseudo']) AND isset($_SESSION['id_user'])){
    header('Location: index.php');
  }

if(isset($_POST["register"])){

$error;
$erreur_message;
$newsletter = 0;

$emailCheck = $dbh->prepare('SELECT COUNT(*) FROM USER WHERE mail = ?');
$emailCheck->execute([$_POST['email']]);
$emailExists = $emailCheck->fetchColumn() > 0;

$pseudoCheck = $dbh->prepare('SELECT COUNT(*) FROM USER WHERE pseudo = ?');
$pseudoCheck->execute([$_POST['pseudo']]);
$pseudoExists = $pseudoCheck->fetchColumn() > 0;

if ( !isset($_POST['lastname']) || empty($_POST['lastname']) ||
			!isset($_POST['firstname']) || empty($_POST['firstname']) ||
			!isset($_POST['email']) || empty($_POST['email']) ||
			!isset($_POST['password']) || empty($_POST['password']) ||
			!isset($_POST['pseudo']) || empty($_POST['pseudo']) ||
			!isset($_POST['password2']) || empty($_POST['password2'])
			 )  {
	$error = 1;
} elseif ( isset($_POST['firstname']) && strlen($_POST['firstname']) < 2) {
    $error = 2;
} elseif ( isset($_POST['lastname']) && strlen($_POST['lastname']) < 2) {
    $error = 3;
} elseif (isset($_POST['email']) && !preg_match('/^[\w\-\.]+@([\w-]+\.)+[\w-]{2,4}$/', $_POST['email']) ) {
    $error = 4;
} elseif (isset($_POST['password']) && !preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/', $_POST['password'])) {
    $error = 5;
} elseif ($_POST['password'] != $_POST['password2']){
	$error = 6;
} elseif ($emailExists) {
    $error = 7;
} elseif ($pseudoExists) {
    $error = 8;
} else {
    $error = 0;
}

//$error = 0;

if ($error == 1) {
    $erreur_message = "Tous les champs doivent être remplis.";
}

if ($error == 2) {
    $erreur_message = "Le prénom entré est invalide.";
}

if ($error == 3) {
    $erreur_message = "Le nom entré est invalide.";
}

if ($error == 4) {
    $erreur_message = "L'adresse mail est invalide.";
}

if ($error == 5) {
      $erreur_message = 'Le mot de passe doit contenir 8 caractères minimum dont une majuscule, une minuscule et un chiffre.';
}
if ($error == 6) {
	$erreur_message = 'Les mots de passe ne correspondent pas.';
}
if ($error == 7) {
    $erreur_message = 'Cette adresse email est déjà utilisée.';
}
if ($error == 8) {
    $erreur_message = 'Ce pseudo est déjà utilisé.';
}

// $error=0;

if ($error == 0) {
    function genererNumeroUnique($dbh) {
        $numero = mt_rand(10000000, 99999999); // on met la plage de numéro à attribuer
        $sqlverif = "SELECT id_user, pseudo, nom, prenom, mail, password, date_ins, role_type, confirm, newsletter FROM USER WHERE id_user = $numero"; // on sélectionne la table et la colonne à vérifier 
        $result = $dbh->query($sqlverif);
        if ($result->num_rows > 0) {
            // Si le numéro existe déjà, on le régénère
            return genererNumeroUnique($dbh);
        } else {
            // Si le numéro est unique, on le retourne
            return $numero;
        }
    }

    // Sauvegarder en BDD

    $num = genererNumeroUnique($dbh);
    $firstname = htmlspecialchars($_POST['firstname']);
    $lastname = htmlspecialchars($_POST['lastname']);
    $password = htmlspecialchars($_POST['password']);
    $email = htmlspecialchars($_POST['email']);
    $pseudo = htmlspecialchars($_POST['pseudo']);
    $date = date("y/m/d");
    $role = "MEMBRE";
    $newsletter = $_POST['newsletter'];

    $passwordHash = password_hash($_POST['password'],  PASSWORD_BCRYPT);

    $sql = "INSERT INTO USER (id_user, pseudo, nom, prenom, mail, password, date_ins, role_type, newsletter)
    VALUES (:id_user, :pseudo, :lastname, :firstname, :email, :password, :date_ins, :role_type, :newsletter)";

    $preparedSql = $dbh->prepare($sql);
    $preparedSql->execute([
        'id_user' => $num,
        'pseudo' => $pseudo,
        'lastname' => $lastname,
        'firstname' => $firstname,
        'email' => $email,
        'password' => $passwordHash,
        'date_ins' => $date,
        'role_type' => $role,
        'newsletter' => $newsletter,
    ]);

    $recupUser = $dbh->prepare('SELECT id_user, pseudo, nom, mail, password, date_ins, role_type, confirm, newsletter FROM USER WHERE mail = ?');
    $recupUser -> execute(array($email));
    if($recupUser->rowCount() > 0) {
        $userInfos = $recupUser->fetch();
        $_SESSION['id_user'] = $userInfos['id_user'];

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

        header('Location: confirmation.php');
        exit();

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
}
}
?>

<!DOCTYPE html>
<html>
    
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Inscription</title>
<!--	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/style_2.css" rel="stylesheet" />
</head>
    
    <style>
    /* Styles pour la fenetre modale */
    .modal {
        display: none; /* Masquer initialement */
        position: fixed;
        z-index: 1;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgb(0,0,0);
        background-color: rgba(0,0,0,0.4);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border: 1px solid #888;
        width: 80%;
        max-width: 300px;
    }

    .close {
        color: #aaa;
        float: right;
        font-size: 28px;
        font-weight: bold;
    }

    .close:hover,
    .close:focus {
        color: black;
        text-decoration: none;
        cursor: pointer;
    }
    /*style pour voir le mot de passe */
    .input-group-text {
        background: none;
        border: none;
    }

    .input-group-append {
        cursor: pointer;}
</style>
    <script>

        let isCaptchaValid = false;

        function showCaptcha() {
            // Affiche la fenêtre modale
            var modal = document.getElementById('captchaModal');
            modal.style.display = 'block';
            document.getElementById('answer').focus();
            // Charge le CAPTCHA dans la section
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'includes/captcha.php', true);
            xhr.onload = function() {
                if (this.status == 200) {
                    document.getElementById('captchaContent').innerHTML = this.responseText;
                }
            };
            xhr.send();
        }
        function closeModal() {
            var modal = document.getElementById('captchaModal');
            modal.style.display = 'none';
        }

        function validateCaptcha() {
            var id_captcha = document.getElementById('id_captcha').value;
            var user_answer = document.getElementById('answer').value;

            // Envoie la reponse du CAPTCHA au serveur pour validation
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'includes/verify_captcha.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (this.status == 200) {
                    if (this.responseText === 'success') {
                        isCaptchaValid = true;
                        captchaValidated();
                    } else {
                        alert('Captcha incorrect. Veuillez réessayer');
                        //alert(id_captcha);
                        //alert(user_answer);
                        closeModal();
                    }
                }
            };
            xhr.send('id_captcha=' + id_captcha + '&user_answer=' + encodeURIComponent(user_answer));

        }

        function captchaValidated() {
            if (isCaptchaValid) {
            // Affiche le bouton de soumission apres validation du CAPTCHA
            document.getElementById('register').style.display = 'block';
            document.getElementById('captchaButton').style.display = 'none';
            closeModal();
        }

        }
        


    </script>
<body class="class-login">
	<div class="container h-100">
		<div class="d-flex justify-content-center h-100">
			<div class="user_card_inscription"> <!-- CSS a  modifier pour agrandir la taille de la carte -->
				<div class="d-flex justify-content-center">
					<div class="brand_logo_container">
                        <a href="index.php">
						    <img src="IMAGES/logo-Amimal.png" class="brand_logo " alt="Logo">
                        </a>
					</div>
				</div>
				<div class="d-flex justify-content-center form_container">
					<form action="" method="POST">
							<div class="row mb-3">
							  <div class="col">
								<input id="firstname" type="text" class="form-control" placeholder="Prénom" name="firstname">
							  </div>
							  <div class="col">
								<input id="lastname" type="text" class="form-control" placeholder="Nom de famille" name="lastname">
							  </div>
							</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-user">
                                    <svg src="IMAGES/person-fill.svg" width="26" height="26" fill="white" class="bi bi-person-fill" viewBox="0 0 16 16">
                                        <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6"/>
                                      </svg>
                                </i></span>
							</div>
							<input id="pseudo" type="text" class="form-control input_user" placeholder="Pseudo" name="pseudo">
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i>
                                    <svg src="IMAGES/envelope-fill.svg" width="26" height="26" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
										<path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
									  </svg>
                                </span>
							</div>
							<input id="email" type="email" class="form-control input_pass" placeholder="Adresse mail" name="email">
						</div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i>
                                    <svg src="IMAGES/key-fill.svg" width="26" height="26" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                      </svg>
                                </span>
							</div>
							<input id="password" type="password" class="form-control input_pass" placeholder="Mot de passe" name="password">
                                
                        </div>
						<div class="input-group mb-3">
							<div class="input-group-append">
								<span class="input-group-text"><i class="fas fa-key"></i>
                                    <svg src="IMAGES/key-fill.svg" width="26" height="26" fill="currentColor" class="bi bi-key-fill" viewBox="0 0 16 16">
                                        <path d="M3.5 11.5a3.5 3.5 0 1 1 3.163-5H14L15.5 8 14 9.5l-1-1-1 1-1-1-1 1-1-1-1 1H6.663a3.5 3.5 0 0 1-3.163 2M2.5 9a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/>
                                      </svg>
                                </span>
							</div>
							<input type="password" class="form-control input_pass" placeholder="Confirmation de mot de passe" name="password2">
						</div>
					</div>
					<span>
                        <?php if(isset($erreur_message)) echo '<span style="color: red; font-size: 12px;">' . $erreur_message . '</span>'?></span>
                    <div class = "text-white">
                        <input class="form-check-input " type="checkbox" id="gridCheck" value = "1" name="newsletter"> S'inscrire à la newsletter
                    </div>
					<div class="d-flex justify-content-center mt-2 login_container">
						<button type="button" id="captchaButton" name="captcha" onclick="showCaptcha()" class="btn btn-primary mx-lg-2 btn-orange"> Vérification

                            <button type="submit" id="register" name="register" class="btn btn-primary mx-lg-2 btn-orange" style="display:none;"> S'inscrire  <svg src="IMAGES/arrow-right-circle.svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
							<path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
						  </svg>
						</button>
				   </div>

					</form>
                <div id="captchaModal" class="modal">
                    <div class="modal-content">
                        <span class="close" onclick="closeModal()">&times;</span>
                        <!-- Contenu du CAPTCHA -->
                        <div id="captchaContent">
                            <?php require_once('includes/captcha.php'); ?>
                        </div>
                        <br>
                        <!-- Bouton pour valider le CAPTCHA -->
                        <button class="btn btn-primary mx-lg-2 btn-orange" type="button" onclick="validateCaptcha()">Valider CAPTCHA</button>
                    </div>
				</div>
				</div>
			</div>
		</div>
	</div>
</body>
</html>

 