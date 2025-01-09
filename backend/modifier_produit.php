<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once("includes/header.php");
include_once('includes/navbar.php');
include_once('includes/journalisations.php');
require('includes/dbpa.php');
include_once('includes/sidenav.php');

global $dbh;

$id_product = isset($_GET['id_product']) ? $_GET['id_product'] : null;

if (!$id_product) {
    header('Location: gestion_boutique.php'); // Rediriger si aucun ID de produit n'est fourni
    exit;
}

// Récupérer les informations du produit
$stmt = $dbh->prepare("SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT WHERE id_product = :id_product");
$stmt->execute([':id_product' => $id_product]);
$produit = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produit) {
    header('Location: gestion_boutique.php'); // Rediriger si le produit n'existe pas
    exit;
}

// Mettre à jour le produit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update'])) {
    $nom_product = $_POST['nom_product'];
    $price_product = $_POST['price_product'];
    $stock = $_POST['stock'];
    $photo = $produit['photo'];
    $description = $_POST['description'];
    $delivery_time = $_POST['delivery_time'];
    $promotion = $_POST['promotion'];
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    // Téléchargement de la nouvelle photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $photo = $uploadFile;
        } else {
            echo "La photo ne s'est pas téléchargée.<br>";
        }
    }

    $stmt = $dbh->prepare("UPDATE PRODUCT SET nom_product = :nom_product, price_product = :price_product, stock = :stock, photo = :photo, description = :description, delivery_time = :delivery_time, promotion = :promotion, rating = :rating, comments = :comments WHERE id_product = :id_product");
    $stmt->execute([
        ':id_product' => $id_product,
        ':nom_product' => $nom_product,
        ':price_product' => $price_product,
        ':stock' => $stock,
        ':photo' => $photo,
        ':description' => $description,
        ':delivery_time' => $delivery_time,
        ':promotion' => $promotion,
        ':rating' => $rating,
        ':comments' => $comments,
        
    ]);

    header('Location: gestion_boutique.php');
    exit;
}
?>


<a href="gestion_boutique.php" class="btn btn-primary float-end mx-3">RETOUR</a>
    <div class="container">
        <h2>Modifier le produit</h2>
        <form method="post" action="" enctype="multipart/form-data" class="styled-form">
            <label for="id_product">ID:</label>
            <input type="text" id="id_product" name="id_product" value="<?php echo htmlspecialchars($produit['id_product']); ?>" required>
            <label for="nom_product">Nom du produit:</label>
            <input type="text" id="nom_product" name="nom_product" value="<?php echo htmlspecialchars($produit['nom_product']); ?>" required>
            
            <label for="price_product">Prix du produit:</label>
            <input type="number" step="0.01" id="price_product" name="price_product" value="<?php echo htmlspecialchars($produit['price_product']); ?>" required>
            
            <label for="stock">Stock:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($produit['stock']); ?>" required>
            
            <label for="photo">Photo actuelle:</label>
            <img src="<?php echo htmlspecialchars($produit['photo']); ?>" alt="Photo" width="100"><br>
            <label for="photo">Changer la photo:</label>
            <input type="file" id="photo" name="photo" accept="image/*">
            
            <label for="description">Description:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($produit['description']); ?></textarea>
            
            <label for="delivery_time">Temps de livraison:</label>
            <input type="text" id="delivery_time" name="delivery_time" value="<?php echo htmlspecialchars($produit['delivery_time']); ?>" required>
            
            <label for="promotion">Promotion:</label>
            <input type="text" id="promotion" name="promotion" value="<?php echo htmlspecialchars($produit['promotion']); ?>">
            
            <label for="rating">Évaluation:</label>
            <input type="number" step="0.1" id="rating" name="rating" value="<?php echo htmlspecialchars($produit['rating']); ?>">
            
            <label for="comments">Commentaires:</label>
            <textarea id="comments" name="comments"><?php echo htmlspecialchars($produit['comments']); ?></textarea>
            
            <button type="submit" class="btn btn-danger" name="update">Mettre à jour</button>
        </form>
    </div>
</body>
<?php include_once('includes/footer.php');
