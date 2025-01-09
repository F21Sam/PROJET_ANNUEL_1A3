<?php
session_start();
require 'dbpa.php';
require_once 'stripe-php-master/init.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_page.php");
    exit();
}

$id_user = $_SESSION['id_user'];
$total = 0;
$nb_product = 0;

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
        $nb_product += $item['quantity'];
    }

    // Configurer Stripe
    \Stripe\Stripe::setApiKey('sk_test_51PSyLDP6kyYnOe3gVUa9Np1iCG4h6KW6PdITiznVVmys3ebdIjn5OrrRN9dt3nWZiTc0UF6E169CxGrX3StOrafL00HqUdAdWX');

    // Vérifier si l'utilisateur a déjà un id_stripe
    $query = $dbh->prepare("SELECT id_stripe FROM USER WHERE id_user = :id_user");
    $query->bindParam(':id_user', $id_user);
    $query->execute();
    $id_stripe = $query->fetchColumn();

    if (!$id_stripe) {
        // Créer un nouveau client Stripe sans email
        $customer = \Stripe\Customer::create();

        $id_stripe = $customer->id;

        // Mettre à jour l'ID Stripe dans la base de données
        $query = $dbh->prepare("UPDATE USER SET id_stripe = :id_stripe WHERE id_user = :id_user");
        $query->bindParam(':id_stripe', $id_stripe);
        $query->bindParam(':id_user', $id_user);
        $query->execute();
    }

    // Créer une session de paiement Stripe
    $session = \Stripe\Checkout\Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => 'eur',
                'product_data' => [
                    'name' => 'Commande de ' . $nb_product . ' produits',
                ],
                'unit_amount' => $total * 100, 
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'customer' => $id_stripe,
        'success_url' => 'https://amimal.freeddns.org/success.php?session_id={CHECKOUT_SESSION_ID}',
        'cancel_url' => 'https://amimal.freeddns.org/cancel.php',
        'metadata' => [
            'user_id' => $id_user,
            'nb_product' => $nb_product,
            'type' => 'commande'
        ]
    ]);

    $insertPanier = $dbh->prepare("INSERT INTO PANIER_TEMP (session_id, id_user, id_product, quantity) VALUES (?, ?, ?, ?)");
    foreach ($_SESSION['cart'] as $item) {
        $insertPanier->execute([$session->id, $id_user, $item['id_product'], $item['quantity']]);
    }

    header("Location: " . $session->url);
    exit();
} else {
    echo "Votre panier est vide.";
}
?>
