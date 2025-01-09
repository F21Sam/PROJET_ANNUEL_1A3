<?php
session_start();

if (isset($_GET['id_product'])) {
    $id_product = $_GET['id_product'];
    if (isset($_SESSION['cart'][$id_product])) {
        unset($_SESSION['cart'][$id_product]);
    }
}

header("Location: ../mon-panier.php");
exit();
?>
