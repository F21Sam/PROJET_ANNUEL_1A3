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
    http_response_code(400);
    exit('Invalid payload');
} catch (\Stripe\Exception\SignatureVerificationException $e) {
    http_response_code(400);
    exit('Invalid signature');
}

if ($event->type == 'checkout.session.completed') {
    $session = $event->data->object;
    $animal_id = $session->metadata->animal_id;
    $user_id = $session->metadata->user_id;

    if (!is_numeric($animal_id) || !is_numeric($user_id)) {
        http_response_code(400);
        exit('Invalid data');
    }

    try {
        $stmt = $dbh->prepare("INSERT INTO SUBSCRIPTION (start_date, id_animal, id_user) VALUES (NOW(), ?, ?)");
        $stmt->execute([$animal_id, $user_id]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo 'Database error: ' . $e->getMessage(); // Afficher le message d'erreur détaillé
        exit();
    }
}

http_response_code(200);
?>
