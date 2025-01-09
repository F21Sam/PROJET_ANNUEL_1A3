<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'dbpa.php';

if (isset($_POST['id_product']) && isset($_POST['quantity'])) {
    $id_product = $_POST['id_product'];
    $quantity = $_POST['quantity'];

    // Vérifier si le produit existe dans la base de données
    $produitRequete = $dbh->prepare("SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT WHERE id_product = ?");
    $produitRequete->execute([$id_product]);
    $product = $produitRequete->fetch(PDO::FETCH_ASSOC);

    if ($product) {
        // Ajouter le produit au panier
        if (isset($_SESSION['cart'][$id_product])) {
            $_SESSION['cart'][$id_product]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id_product] = [
                "id_product" => $product['id_product'],
                "name" => $product['nom_product'],
                "price" => $product['price_product'],
                "quantity" => $quantity
            ];
        }
        header("Location: ../mon-panier.php");
        exit();
    } else {
        // Produit non trouvé
        echo "Produit non trouvé.";
    }
} else {
    // Paramètres non valides
    echo "Paramètres non valides.";
}
?>
