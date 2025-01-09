<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if(!isset($_SESSION['pseudo']) AND !isset($_SESSION['id_user'])){
  header('Location: login_page_forum.php');
}
require_once 'stripe-php-master/init.php';
require 'dbpa.php';

\Stripe\Stripe::setApiKey('sk_test_51PSyLDP6kyYnOe3gVUa9Np1iCG4h6KW6PdITiznVVmys3ebdIjn5OrrRN9dt3nWZiTc0UF6E169CxGrX3StOrafL00HqUdAdWX');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['id_user'];

    // Récupérer l'ID de l'animal
    $animal_id = $_GET['id_animal'];

    // Vérifier si l'utilisateur a déjà un id_stripe
    $stmt = $dbh->prepare("SELECT id_stripe FROM USER WHERE id_user = :id_user");
    $stmt->bindParam(':id_user', $user_id);
    $stmt->execute();
    $id_stripe = $stmt->fetchColumn();

    if (!$id_stripe) {
        // Créer un nouveau client Stripe sans email
        $customer = \Stripe\Customer::create();

        $id_stripe = $customer->id;

        // Mettre à jour l'ID Stripe dans la base de données
        $stmt = $dbh->prepare("UPDATE USER SET id_stripe = :id_stripe WHERE id_user = :id_user");
        $stmt->bindParam(':id_stripe', $id_stripe);
        $stmt->bindParam(':id_user', $user_id);
        $stmt->execute();
    }

    // Créer une session de paiement Stripe
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Parrainage Animal ' . $animal_id,
                ],
                'unit_amount' => 999, // Montant en centimes !!
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'customer' => $id_stripe,
        'success_url' => 'https://amimal.freeddns.org/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://yourdomain.com/cancel.php',
        'metadata' => [
            'animal_id' => $animal_id,
            'user_id' => $user_id,
            'type' => 'parrainage'
        ]
    ]);

    header("Location: " . $session->url);
    exit();
}
?>
