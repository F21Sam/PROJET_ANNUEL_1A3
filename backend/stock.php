<?php include_once('includes/journalisations.php');
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
//includes
    include_once("includes/header.php");
    include_once('includes/navbar.php');
    include_once('includes/journalisations.php'); 
    require('includes/dbpa.php');
    include_once('includes/sidenav.php');


global $dbh;

// Mise à jour du stock
if (isset($_POST['update-stock-btn'])) {
    $id_product = $_POST['id_product'];
    $new_stock = $_POST['new_stock'];

    $query = "UPDATE PRODUCT SET stock = :new_stock WHERE id_product = :id_product";
    $statement = $dbh->prepare($query);
    $statement->execute(['new_stock' => $new_stock, 'id_product' => $id_product]);

    echo "<div class='alert alert-success'>Stock mis à jour avec succès pour le produit ID: $id_product</div>";
}

// Retirer de la promotion
if (isset($_POST['remove-promotion-btn'])) {
    $id_product = $_POST['id_product'];

    $query = "UPDATE PRODUCT SET promotion = 0 WHERE id_product = :id_product";
    $statement = $dbh->prepare($query);
    $statement->execute(['id_product' => $id_product]);

    echo "<div class='alert alert-success'>Promotion retirée avec succès pour le produit ID: $id_product</div>";
}

// Récupération des produits
$stmt = $dbh->query("SELECT id_product, nom_product, stock FROM PRODUCT ORDER BY id_product ASC");
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des produits en promotion
$stmtPromotion = $dbh->query("SELECT id_product, nom_product, stock, promotion FROM PRODUCT WHERE promotion = 1 ORDER BY id_product ASC");
$promotedProducts = $stmtPromotion->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
<h1>Gestion du stock des produits</h1>
<a href="accueil.php" class="btn btn-primary float-end m-5">RETOUR</a>
    <div class="container">
    
    <h2>Produits</h2>
    <table class="table">
    <thead>
        <tr>
            <th>ID Produit</th>
            <th>Nom du Produit</th>
            <th>Stock Actuel</th>
            <th>Nouveau Stock</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($products as $product) : ?>
            <tr>
                <form method="post" action="">
                    <td><?php echo $product['id_product']; ?></td>
                    <td><?php echo $product['nom_product']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td>
                        <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" class="form-control">
                        <input type="hidden" name="id_product" value="<?php echo $product['id_product']; ?>">
                    </td>
                    <td>
                        <button type="submit" class="btn btn-primary btn-sm" name="update-stock-btn">Mettre à jour</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
    </div>

<div class="container">
<h2>Produits en Promotion</h2>
<table class="table">
    <thead>
        <tr>
            <th>ID Produit</th>
            <th>Nom du Produit</th>
            <th>Stock</th>
            <th>Promotion</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($promotedProducts as $product) : ?>
            <tr>
                <form method="post" action="">
                    <td><?php echo $product['id_product']; ?></td>
                    <td><?php echo $product['nom_product']; ?></td>
                    <td><?php echo $product['stock']; ?></td>
                    <td><?php echo $product['promotion'] ? 'Oui' : 'Non'; ?></td>
                    <td>
                        <input type="hidden" name="id_product" value="<?php echo $product['id_product']; ?>">
                        <button type="submit" class="btn btn-warning btn-sm" name="remove-promotion-btn">Retirer de la Promotion</button>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
</div>


</div>






<?php include_once("includes/footer.php"); ?>
