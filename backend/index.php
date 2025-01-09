<?php
session_start();
include_once("includes/header.php");
include_once('includes/journalisations.php');
include_once("includes/dbpa.php");



use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'TESTEMAIL/phpmailer/src/Exception.php';
require 'TESTEMAIL/phpmailer/src/PHPMailer.php';
require 'TESTEMAIL/phpmailer/src/SMTP.php';


if (!isset($dbh)) {
    die("Erreur de connexion à la base de données.");
}

if (isset($_POST['connect'])) {
    if (isset($_POST['password']) && isset($_POST['mail'])) {
        $sql = 'SELECT id_user, pseudo, nom, mail, password, date_ins, role_type, confirm, newsletter, banned FROM USER WHERE mail = :email';
        $preparedSql = $dbh->prepare($sql);
        $preparedSql->execute(['email' => $_POST['mail']]);
        $user = $preparedSql->fetch();
        $email = $_POST['mail'];

        if ($user) {
            if (password_verify($_POST['password'], $user['password'])) {
                if ($user['confirm'] == 1) {
                    if ($user['role_type'] == 'ADMIN') {
                        $dateLogin = 'UPDATE USER SET login_date = NOW() WHERE id_user = :id_user';
                        $preparedSqlDate = $dbh->prepare($dateLogin);
                        $preparedSqlDate->execute(['id_user' => $user['id_user']]);

                    if ($user['banned'] == 1) {
                        $_SESSION['message'] = "";
                        header("Location: /PHP-entrainement/PROJET_ANNUEL/Amimal/banni.php");
                        exit();
                    } else {
                        $_SESSION['id_user'] = $user['id_user'];
                        $_SESSION['role_type'] = $user['role_type'];
                        header("Location: accueil.php");
                        exit();
                    }
                } else {
                    $error = "Vous n'avez pas les droits d'administrateur.";
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
                        $mail->Body = "<a href='https://amimal.freeddns.org/Includes/verif_insc.php?num=" . $user['id_user'] . "'>Cliquez ici pour confirmer votre compte.</a>";

                        $mail->send();
                    } catch (Exception $e) {
                        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
                    }
                }
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Cet utilisateur n'existe pas.";
        }
    }
}
?>

<div class="body">
    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="row border rounded-5 p-3 bg-white shadow box-area">
            <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box">
                <img src="assets/images/panda.png" class="img-fluid" style="width: 500px;">
            </div>

            <div class="col-md-6 right-box">
                <div class="row align-items-center">
                    <div class="header-text mb-4">
                        <h2>Bonjour,</h2>
                        <p>Bienvenue sur la page de gestion d'Amimal !</p>
                    </div>
                    <?php if (isset($error)) { echo "<div class='alert alert-danger'>$error</div>"; } ?>
                    <form method="POST" action="">
                        <div class="input-group mb-3">
                            <input type="email" name="mail" class="form-control form-control-lg bg-light fs-6" placeholder="Adresse mail" required>
                        </div>
                        <div class="input-group mb-1">
                            <input type="password" name="password" class="form-control form-control-lg bg-light fs-6" placeholder="Mot de passe" required>
                        </div>
                        <div class="input-group mb-5 d-flex justify-content-between">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="formCheck">
                                <label for="formCheck" class="form-check-label text-secondary"><small>Se souvenir de moi ?</small></label>
                            </div>
                            <div class="forgot">
                                <small><a href="#">Mot de passe oublié ?</a></small>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <button type="submit" name="connect" class="btn btn-lg btn-primary w-100 fs-6">Se connecter</button>
                        </div>
                    </form>
                    <div class="row">
                        <small>Vous n'avez pas encore de compte ? <a href="/Amimal/inscription.php">Cliquez ici</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>
