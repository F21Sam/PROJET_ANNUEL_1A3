<?php
require_once 'stripe-php-master/init.php';
require 'dbpa.php';

\Stripe\Stripe::setApiKey('sk_test_51PSyLDP6kyYnOe3gVUa9Np1iCG4h6KW6PdITiznVVmys3ebdIjn5OrrRN9dt3nWZiTc0UF6E169CxGrX3StOrafL00HqUdAdWX');

$endpoint_secret = 'whsec_I9u6Cv0uiftOIiBIjOOJGarT0kKequxf';

$payload = @file_get_contents('php://input');
$sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'] ?? '';

if (empty($sig_header)) {
    http_response_code(400);
    exit('Missing Stripe signature');
}

try {
    $event = \Stripe\Webhook::constructEvent(
        $payload, $sig_header, $endpoint_secret
    );
} catch (\UnexpectedValueException $e) {
    error_log('Invalid payload: ' . $e->getMessage());
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    error_log('Invalid signature: ' . $e->getMessage());
    http_response_code(400);
    exit('Invalid signature');
}

if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;

    if ($session->metadata->type == 'parrainage') {
        // Gestion du parrainage
        $animal_id = $session->metadata->animal_id;
        $user_id = $session->metadata->user_id;

        if (!is_numeric($animal_id) || !is_numeric($user_id)) {
            http_response_code(400);
            exit('Invalid data');
        }

        try {
            $requeteSubscription = $dbh->prepare("INSERT INTO SUBSCRIPTION (start_date, id_animal, id_user) VALUES (NOW(), ?, ?)");
            $requeteSubscription->execute([$animal_id, $user_id]);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            http_response_code(500);
            echo 'Database error: ' . $e->getMessage();
            exit();
        }
    } elseif ($session->metadata->type == 'commande') {
        // Gestion de la commande
        $user_id = $session->metadata->user_id;
        $total = $session->amount_total / 100;
        $nb_product = $session->metadata->nb_product;

        try {
            // Créer une nouvelle commande
            $requeteCommande = $dbh->prepare("INSERT INTO COMMAND (date_command, price, nb_product, id_user) VALUES (NOW(), ?, ?, ?)");
            $requeteCommande->execute([$total, $nb_product, $user_id]);
            $id_command = $dbh->lastInsertId();

            $requetePanier = $dbh->prepare("SELECT id_product, quantity FROM PANIER_TEMP WHERE session_id = ?");
            $requetePanier->execute([$session->id]);
            $cart_items = $requetePanier->fetchAll(PDO::FETCH_ASSOC);

            // Insérer les articles de la commande
            $requeteDetail = $dbh->prepare("INSERT INTO COMMAND_DETAIL (id_product, id_command, quantity) VALUES (?, ?, ?)");
            foreach ($cart_items as $item) {
                error_log("Inserting product ID: {$item['id_product']}, Command ID: {$id_command}, Quantity: {$item['quantity']}");
                $requeteDetail->execute([$item['id_product'], $id_command, $item['quantity']]);
            }

            $requeteSupprPanier = $dbh->prepare("DELETE FROM PANIER_TEMP WHERE session_id = ?");
            $requeteSupprPanier->execute([$session->id]);

            // Vider le panier
            unset($_SESSION['cart']);
        } catch (PDOException $e) {
            error_log('Database error: ' . $e->getMessage());
            http_response_code(500);
            echo 'Database error: ' . $e->getMessage();
            exit();
        }
    }
}

http_response_code(200);
?>
