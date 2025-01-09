<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once 'includes/stripe-php-master/init.php';
require_once 'includes/dbpa.php'; // Assurez-vous d'avoir votre fichier de connexion à la base de données

\Stripe\Stripe::setApiKey('sk_test_51PSyLDP6kyYnOe3gVUa9Np1iCG4h6KW6PdITiznVVmys3ebdIjn5OrrRN9dt3nWZiTc0UF6E169CxGrX3StOrafL00HqUdAdWX');

$success_message = "";
$error_message = "";

if (isset($_GET['session_id'])) {
    $session_id = $_GET['session_id'];

    // Récupérer la session Stripe Checkout
    try {
        $session = \Stripe\Checkout\Session::retrieve($session_id);
        $customer = \Stripe\Customer::retrieve($session->customer);

        // Récupérer les métadonnées
        $animal_id = $session->metadata->animal_id ?? null;
        $user_id = $session->metadata->user_id;

        // Message de confirmation
        $success_message = "Votre commande a été traitée avec succès. Merci pour votre achat !";

        // Vider le panier après le succès de la commande
        unset($_SESSION['cart']);

    } catch (\Stripe\Exception\ApiErrorException $e) {
        $error_message = "Il y a eu une erreur en récupérant les détails de votre session de paiement. Veuillez contacter le support.";
    }
} else {
    $error_message = "La session de paiement est manquante. Veuillez contacter le support.";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Succès de la commande</title>
    <style>
        body {
            background-color: #183425;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
            flex-direction: column;
            background-color: #183425;
            padding: 10px;
        }
        .header-text {
            margin-bottom: 20px;
        }
        .header-text h1 {
            font-size: 72px;
            margin: 0;
            color: #BC644B;
        }
        h2 {
            font-size: 24px;
            margin: 0;
            color: #f2d8be;
        }
        p {
            margin: 20px 0;
            color: #fff;
        }
        a.btn-home {
            background-color: #BC644B;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        a.btn-home:hover {
            background-color: #a22300;
        }
        .left-box, .right-box {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .right-box img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
            margin-top: 30px;
        }
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
                justify-content: space-between;
            }
            .left-box, .right-box {
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-box">
            <h2><?php echo $success_message ? "Commande réussie" : "Erreur"; ?></h2>
            <p><?php echo $success_message ?: $error_message; ?></p>
            <a href="index.php" class="btn-home">Retour à l'accueil</a>
        </div>
        <div class="right-box">
            <img src="IMAGES/panda-rouge-dessin-anime-visage-triste_853115-1058.jpeg" alt="Panda roux heureux">
        </div>
    </div>
</body>
</html>
