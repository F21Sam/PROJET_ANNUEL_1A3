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

// Filtrage et tri

$order = isset($_GET['order']) ? $_GET['order'] : 'id_product';
$dir = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';

$query = "SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT WHERE 1";
$params = [];

$query .= " ORDER BY $order $dir";

$stmt = $dbh->prepare($query);
$stmt->execute($params);
$produits = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Ajouter un produit
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $id_product = $_POST['id_product'];
    $nom_product = $_POST['nom_product'];
    $price_product = $_POST['price_product'];
    $stock = $_POST['stock'];
    $photo = '';
    $description = $_POST['description'];
    $delivery_time = $_POST['delivery_time'];
    $promotion = $_POST['promotion'];
    $rating = $_POST['rating'];
    $comments = $_POST['comments'];

    // Téléchargement de la photo
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $photo = $uploadFile;
        } else {
            echo "La photo ne s'est pas téléchargée.<br>";
        }
    }

    if ($photo) {
        $stmt = $dbh->prepare("INSERT INTO PRODUCT (id_product,nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments) 
                            VALUES (:id_product,:nom_product, :price_product, :stock, :photo, :description, :delivery_time, :promotion, :rating, :comments)");
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
            ':comments' => $comments
        ]);

        header('Location: ' . $_SERVER['PHP_SELF']);
        exit;
    }
}

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id_product = $_GET['delete'];
    $stmt = $dbh->prepare("DELETE FROM PRODUCT WHERE id_product = :id_product");
    $stmt->execute([':id_product' => $id_product]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>

    <div class="container">
        <div class="container">
            <h2>Liste des produits</h2>
            <table class="table">
                <thead>
                    <tr>
                    <th><a href="?order=id_product&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>ID</th>
                    <th><a href="?order=nom_product&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>NOM</th>
                    <th><a href="?order=price_product&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>PRIX</th>
                    <th><a href="?order=stock&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>STOCK</th>
                    <th>Photo</th>
                    <th>Description</th>
                    <th><a href="?order=delivery_time&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>LIVRAISON</th>
                    <th><a href="?order=promotion&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>PROMOTION</th>
                    <th><a href="?order=rating&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>ÉVALUATION</th>
                    <th>Commentaires</th>
                    <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($produits as $produit) : ?>
                        <tr>
                            <td><?php echo htmlspecialchars($produit['id_product']); ?></td>
                            <td><?php echo htmlspecialchars($produit['nom_product']); ?></td>
                            <td><?php echo htmlspecialchars($produit['price_product']); ?></td>
                            <td><?php echo htmlspecialchars($produit['stock']); ?></td>
                            <td><img src="<?php echo htmlspecialchars($produit['photo']); ?>" alt="Photo" width="50"></td>
                            <td class="desc-column"><?php echo htmlspecialchars($produit['description']); ?></td>
                            <td class="liv-column"><?php echo htmlspecialchars($produit['delivery_time']); ?></td>
                            <td><?php echo htmlspecialchars($produit['promotion']); ?></td>
                            <td><?php echo htmlspecialchars($produit['rating']); ?></td>
                            <td class="com-column"><?php echo htmlspecialchars($produit['comments']); ?></td>
                            <td>
                                <a href="modifier_produit.php?id_product=<?php echo $produit['id_product']; ?>" class="btn btn-primary">Modifier</a>
                                <a href="?delete=<?php echo $produit['id_product']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <div class="box">
            <h2>Ajouter un produit</h2>
            <form method="post" action="" enctype="multipart/form-data"  class="styled-form">

                <label for="id_product">ID:</label>
                <input type="text" id="id_product" name="id_product" required>

                <label for="nom_product">Nom du produit:</label>
                <input type="text" id="nom_product" name="nom_product" required>
                
                <label for="price_product">Prix du produit:</label>
                <input type="number" step="0.01" id="price_product" name="price_product" required>
                
                <label for="stock">Stock:</label>
                <input type="number" id="stock" name="stock" required>
                
                <label for="photo">Photo:</label>
                <input type="file" id="photo" name="photo" accept="image/*" required>
                
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea>
                
                <label for="delivery_time">Temps de livraison:</label>
                <input type="text" id="delivery_time" name="delivery_time" required>
                
                <label for="promotion">Promotion:</label>
                <input type="text" id="promotion" name="promotion">
                
                <label for="rating">Évaluation:</label>
                <input type="number" step="0.1" id="rating" name="rating">
                
                <label for="comments">Commentaires:</label>
                <textarea id="comments" name="comments"></textarea>
                
                <button type="submit" class="btn btn-danger" name="add">Ajouter</button>
            </form>
        </div>
    </div>
</body>
<style>
    
    /* Table container styling */
    .table-container {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        margin: 20px 0;
    }

    /* Table styling */
    .table {
        width: 90%;
        margin: 0 auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
    }

    /* Table header styling */
    .table thead th {
        background-color: #ea1e63;
        color: #ffffff;
        text-align: left;
        padding: 12px;
    }

    /* Table row and cell styling */
    .table tbody tr {
        border-bottom: 1px solid #dddddd;
    }

    .table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
    }

    .table tbody td {
        padding: 12px;
        text-align: left;
    }

    .table tbody td.com-column {
                max-width: 600px; 
                white-space: wrap; 
            }
            .table tbody td.desc-column {
                max-width: 600px; 
                white-space: wrap; 
            }
            .table tbody td.liv-column {
                max-width: 600px; 
                white-space: wrap; 
            }
    /*l'icone sort */
    .table th a {
    text-decoration: none; 
    color: #ffffff; 
    text-align: center;

    }
    .table th a i {
        font-size: 14px; 
        vertical-align: middle; 

    }
    .table th a:hover {
            color: #000;
            transition: background-color 0.3s ease;
    }
</style>
<?php include_once('includes/footer.php');
