<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projet_Annuel_Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/mon-compte.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; ?>
  </header>
  <main>
    <section class="headband">
      <div class="container">
        <h1>Mon panier</h1>
      </div>
    </section>

    
    <section class="section-1">
    <div class="container-fluid">
        <div class="row">
            <?php include "includes/account_navbar.php"; ?>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
            <h2>Mon panier</h2>
            <p>Votre panier est vide.</p>
            <div class="container mt-5">
        <h1>Mon Panier</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Produit</th>
                    <th>Prix</th>
                    <th>Quantité</th>
                    <th>Total</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $total = 0;
                if (!empty($_SESSION['cart'])) {
                    foreach ($_SESSION['cart'] as $item) {
                        $item_total = $item['price'] * $item['quantity'];
                        $total += $item_total;
                        echo "<tr>
                            <td>{$item['name']}</td>
                            <td>{$item['price']}€</td>
                            <td>{$item['quantity']}</td>
                            <td>{$item_total}€</td>
                            <td><a href='includes/suppr_panier.php?id_product={$item['id_product']}' class='btn btn-danger'>Supprimer</a></td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='5'>Votre panier est vide.</td></tr>";
                }
                ?>
                <tr>
                    <td colspan="3"><strong>Total</strong></td>
                    <td><strong><?php echo $total; ?>€</strong></td>
                    <td><a href="includes/commande.php" class="btn btn-primary">Passer la commande</a></td>
                </tr>
            </tbody>
        </table>
    </div>
            </main>
        </div>
    </div>

    
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