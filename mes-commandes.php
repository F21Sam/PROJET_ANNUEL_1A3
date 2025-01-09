<?php
session_start();
require 'includes/dbpa.php';

if (!isset($_SESSION['id_user'])) {
    header("Location: login_page.php");
    exit();
}

$id_user = $_SESSION['id_user'];

try {
    $recupCommand = $dbh->prepare("
        SELECT c.id_command, c.date_command, c.price, c.nb_product, cd.id_product, cd.quantity, p.nom_product, p.price_product
        FROM COMMAND c
        JOIN COMMAND_DETAIL cd ON c.id_command = cd.id_command
        JOIN PRODUCT p ON cd.id_product = p.id_product
        WHERE c.id_user = :id_user
        ORDER BY c.date_command DESC
    ");
    $recupCommand->execute([':id_user' => $id_user]);
    $commandes = $recupCommand->fetchAll(PDO::FETCH_ASSOC);
    
    $grouped_commandes = [];
    foreach ($commandes as $commande) {
        $grouped_commandes[$commande['id_command']]['details'] = [
            'date_command' => $commande['date_command'],
            'price' => $commande['price'],
            'nb_product' => $commande['nb_product']
        ];
        $grouped_commandes[$commande['id_command']]['products'][] = [
            'id_product' => $commande['id_product'],
            'nom_product' => $commande['nom_product'],
            'quantity' => $commande['quantity'],
            'price_product' => $commande['price_product']
        ];
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}
?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Historique des Commandes</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/mon-compte.css" rel="stylesheet" />
    <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
    <link href="STYLE/dark_mode.css" rel="stylesheet" />
    <style>
        body {
            font-size: 0.9rem;
        }
        h1, h2, h3, h4 {
            font-size: 1.1rem;
        }
        .table th, .table td {
            font-size: 0.85rem;
        }
        .main-content p {
            font-size: 0.85rem;
        }
    </style>
</head>
<body>
<header>
    <?php include "includes/main_navbar.php"; ?>
</header>
<main>
    <section class="headband">
        <div class="container">
            <h1>Historique des Commandes</h1>
        </div>
    </section>
    <section class="section-1">
        <div class="container-fluid">
            <div class="row">
                <?php include "includes/account_navbar.php"; ?>
                <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
                    <h2>Mes Commandes</h2>
                    <?php if (!empty($grouped_commandes)): ?>
                        <?php foreach ($grouped_commandes as $id_command => $commande): ?>
                            <div class="mb-4">
                                <h4>Commande n°<?php echo $id_command; ?> - Date : <?php echo $commande['details']['date_command']; ?></h4>
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>Produit</th>
                                            <th>Quantité</th>
                                            <th>Prix Unitaire</th>
                                            <th>Prix Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($commande['products'] as $product): ?>
                                            <tr>
                                                <td><?php echo $product['nom_product']; ?></td>
                                                <td><?php echo $product['quantity']; ?></td>
                                                <td><?php echo number_format($product['price_product'], 2); ?> €</td>
                                                <td><?php echo number_format($product['quantity'] * $product['price_product'], 2); ?> €</td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <p><strong>Total Commande : <?php echo number_format($commande['details']['price'], 2); ?> €</strong></p>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p>Aucune commande trouvée.</p>
                    <?php endif; ?>
                </main>
            </div>
        </div>
    </section>
</main>
<?php include "includes/newsletter_front.php"; ?>
<footer>
    <?php include "includes/footer_front.php"; ?>
</footer>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<?php include "includes/dark_mode.php"; ?>
</body>
</html>
