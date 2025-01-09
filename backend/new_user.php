<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}

include_once("includes/header.php");
include_once('includes/navbar.php');
include_once('includes/journalisations.php');
require_once('includes/dbpa.php');
include_once('includes/sidenav.php');

global $dbh;

if(isset($_POST["register"])) {

    $error = null;
    $erreur_message = null;
    $newsletter = 0;

    if ( !isset($_POST['lastname']) || empty($_POST['lastname']) ||
         !isset($_POST['firstname']) || empty($_POST['firstname']) ||
         !isset($_POST['email']) || empty($_POST['email']) ||
         !isset($_POST['password']) || empty($_POST['password']) ||
         !isset($_POST['pseudo']) || empty($_POST['pseudo']) ||
         !isset($_POST['password2']) || empty($_POST['password2'])
    ) {
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
    } else {
        $error = 0;
    }

    if ($error == 1) {
        $erreur_message = "Tous les champs doivent être remplis.";
    } elseif ($error == 2) {
        $erreur_message = "Le prénom entré est invalide.";
    } elseif ($error == 3) {
        $erreur_message = "Le nom entré est invalide.";
    } elseif ($error == 4) {
        $erreur_message = "L'adresse mail est invalide.";
    } elseif ($error == 5) {
        $erreur_message = 'Le mot de passe doit contenir 8 caractères minimum dont une majuscule, une minuscule et un chiffre.';
    } elseif ($error == 6) {
        $erreur_message = 'Les mots de passe ne correspondent pas.';
    }

    if ($error == 0) {
        function genererNumeroUnique($dbh) {
            $numero = mt_rand(10000000, 99999999); // on met la plage de numéro à attribuer
            $sqlverif = "SELECT id_user FROM USER WHERE id_user = :numero"; // on sélectionne la table et la colonne à vérifier 
            $stmt = $dbh->prepare($sqlverif);
            $stmt->execute(['numero' => $numero]);
            if ($stmt->rowCount() > 0) {
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
        $newsletter = isset($_POST['newsletter']) ? 1 : 0;

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
        $recupUser->execute([$email]);
        if($recupUser->rowCount() > 0) {
            $userInfos = $recupUser->fetch();
            $_SESSION['id_user'] = $userInfos['id_user'];
        }
        header('Location: gestion_utilisateurs.php');
        exit();
    }
}
?>

<a href="gestion_utilisateurs.php" class="btn btn-primary float-end">RETOUR</a>
<div class="container">
<h4>Ajouter un utilisateur à la base de données</h4>
    <div class="card-body">
        <form action="" method="POST" class="styled-form">
            <input id="firstname" type="text" class="form-control" placeholder="Prénom" name="firstname">
            <input id="lastname" type="text" class="form-control" placeholder="Nom de famille" name="lastname">
            <input id="pseudo" type="text" class="form-control input_user" placeholder="Pseudo" name="pseudo">
            <input id="email" type="email" class="form-control input_pass" placeholder="Adresse mail" name="email">
            <input id="password" type="password" class="form-control input_pass" placeholder="Mot de passe" name="password">
            <input type="password" class="form-control input_pass" placeholder="Confirmation de mot de passe" name="password2">
            <label><input type="checkbox" name="newsletter" value="1"> S'abonner à la newsletter</label>
            <?php if (isset($erreur_message)) echo '<span style="color: red; font-size: 12px;">' . $erreur_message . '</span>' ?>
            <button type="submit" id="register" name="register" class="btn btn-primary">Ajouter</button>
        </form>
    </div>
</div>

<?php include_once("includes/footer.php"); ?>
