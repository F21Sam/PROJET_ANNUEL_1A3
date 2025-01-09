<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/dbpa.php');

if (isset($_GET['id_product']) && !empty($_GET['id_product'])) {
    $product_id = $_GET['id_product'];

    $query = $dbh->prepare('SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT WHERE id_product = ?');
    $query->execute([$product_id]);
    $product = $query->fetch(PDO::FETCH_ASSOC);

    if (!$product) {
        header('Location: boutique.php');
        exit();
    }
} else {
    header('Location: boutique.php');
    exit();
}
?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Amimal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/infos-animal.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
  <link rel="icon" href="IMAGES/logo-icon.png" type="image/png">
</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; ?>
  </header>
  <main>

    <section class="section-1">
      <div class="container">
        <div class="row">

          <div class="col-12 col-lg-6 custom-img">
            <div class="image-container">
              <img src="backend/<?= htmlspecialchars($product['photo']); ?>" alt="<?= htmlspecialchars($product['nom_product']); ?>" class="img-fluid" width="80%" height="80%" />
            </div>
          </div>

          <div class="col-12 col-lg-5 custom-text info">
            <a href="boutique.php" class="lien-back"><  Tous nos produits</a>
            <p class="espece-p"><?= htmlspecialchars($product['price_product']); ?>€</p>
            <h2 class="orange"><?= htmlspecialchars($product['nom_product']); ?></h2>

            <p><?= htmlspecialchars($product['description']); ?></p>
            <p>Stock : <?= htmlspecialchars($product['stock']); ?></p>
            <p>Délai de livraison : <?= htmlspecialchars($product['delivery_time']); ?> jours</p>
            <p>Promotion : <?= htmlspecialchars($product['promotion']); ?>%</p>
            <p>Évaluation : <?= htmlspecialchars($product['rating']); ?> / 5</p>
            <p>Commentaires : <?= htmlspecialchars($product['comments']); ?></p>

            <?php if (isset($_SESSION['id_user'])): ?>
            <form action="includes/ajout_panier.php" method="post">
              <input type="hidden" name="id_product" value="<?= htmlspecialchars($product['id_product']); ?>">
              <div class="form-group">
                <label for="quantity">Quantité :</label>
                <input type="number" id="quantity" name="quantity" value="1" min="1" max="<?= htmlspecialchars($product['stock']); ?>" class="form-control">
              </div>
              <button type="submit" class="btn btn-primary savoir-plus-btn btn-orange">Ajouter au panier<img src="IMAGES/right-arrow.png" class="btn-icon"></button>
            </form>
            <?php else: ?>
            <p class="text-danger">Veuillez vous connecter pour ajouter ce produit au panier.</p>
            <?php endif; ?>
          </div>

        </div>
    </section>

    <section class="section-2">
      <div class="container">
        <div class="row">
          
          <h2 class="titre vert">Description complète</h2>
          <p><?= htmlspecialchars($product['description']); ?></p>

        </div>
    </section>

    <?php include "includes/newsletter_front.php"; ?>
    
    
  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>

  <?php include "includes/dark_mode.php"; ?>
  
</body>

</html>
