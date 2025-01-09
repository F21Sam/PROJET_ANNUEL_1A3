<?php
session_start();
include ('dbpa.php');

try {
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = $_POST['email'];

        $requeteUser = $dbh->prepare("SELECT id_user, pseudo, mail FROM USER WHERE mail = :email");
        $requeteUser->bindParam(':email', $email);
        $requeteUser->execute();
        $result = $requeteUser->fetchAll(PDO::FETCH_ASSOC);

        if (count($result) > 0) {
            $update_stmt = $dbh->prepare("UPDATE USER SET newsletter = 1 WHERE mail = :email");
            $update_stmt->bindParam(':email', $email);
            if ($update_stmt->execute()) {
                $_SESSION['success_message'] = "Vous êtes maintenant inscrit à la newsletter.";
            } else {
                $_SESSION['error_message'] = "Une erreur s'est produite. Veuillez réessayer.";
            }
            header("Location: ../inscri_newsletter.php");
            exit();
        } else {
            header("Location: ../inscription.php");
            exit();
        }
    } else {
        echo "Adresse email non valide.";
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
