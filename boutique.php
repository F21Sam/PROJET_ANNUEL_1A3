<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/dbpa.php'); 

$productsBdd = $dbh->prepare('SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT');
$productsBdd->execute();
$products = $productsBdd->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Amimal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/boutique.css" rel="stylesheet" />
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
        <h1>Boutique</h1>
      </div>
    </section>

    <section class="section-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                
            
                <div class="search-column">
                    <h3 class="titre-colone-search">Rechercher</h3>
                    
                    <div class="input-group">
                        <input type="text" class="input-text" placeholder="Recherchez un produit" id="rechercheInput" aria-label="mail">
                        
                        <div class="input-group-append">
                            <button class="btn send-input-btn btn-primary" type="button" id="rechercheButton">Envoyer</button>
                        </div>
                    </div>

                    <button class="btn reset-btn btn-primary btn-orange" type="button" id="resetButton">Réinitialiser</button>
                </div>


            </div>
            <div class="col-lg-9">
                <div class="container">
                    <div class="grid-container" id="results">
                    <?php foreach($products as $product): ?>
                        <a href="infos-produit.php?id_product=<?= $product['id_product']; ?>">
                            <div class="fiche-produit">
                                <div class="image-container">
                                    <img src="backend/<?= htmlspecialchars($product['photo']); ?>" class="img-profil-animal">
                                </div>
                                <p class="orange"><?= htmlspecialchars($product['price_product']); ?>€</p>
                                <h2><?= htmlspecialchars($product['nom_product']); ?></h2>
                            </div>
                        </a>
                    <?php endforeach; ?>

                    </div>    
                </div>
            </div>
        </div>
    </div>
</section>

    
    <?php include "includes/newsletter_front.php"; ?>
  
  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>

  <?php include "includes/dark_mode.php"; ?>
  <script>
    const rechercheInput = document.getElementById('rechercheInput');
    const rechercheButton = document.getElementById('rechercheButton');
    const resultsDiv = document.getElementById('results');

    function displayResults(data) {
        resultsDiv.innerHTML = '';

        if (data.error) {
            resultsDiv.innerHTML = `<p>Erreur: ${data.error}</p>`;
            return;
        }

        if (data.length === 0) {
            resultsDiv.innerHTML = '<p>Aucun résultat trouvé.</p>';
            return;
        }

        data.forEach(item => {
            const productDiv = document.createElement('div');
            productDiv.className = 'fiche-produit';

            const link = document.createElement('a');
            link.href = `infos-produit.php?id_product=${item.id_product}`;

            const imgDiv = document.createElement('div');
            imgDiv.className = 'image-container';

            const img = document.createElement('img');
            img.src = 'backend/' + item.photo;
            img.className = 'img-profil-animal';
            imgDiv.appendChild(img);

            const priceP = document.createElement('p');
            priceP.className = 'orange';
            priceP.textContent = `${item.price_product}€`;

            const nameH2 = document.createElement('h2');
            nameH2.textContent = item.nom_product;

            link.appendChild(imgDiv);
            link.appendChild(priceP);
            link.appendChild(nameH2);
            productDiv.appendChild(link);

            resultsDiv.appendChild(productDiv);
        });
    }

    function fetchResults(recherche = '') {
        fetch(`includes/recherche_produit.php?recherche=${encodeURIComponent(recherche)}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                console.log('Données reçues:', data);
                displayResults(data);
            })
            .catch(error => {
                console.error('Erreur:', error);
                resultsDiv.innerHTML = '<p>Une erreur est survenue.</p>';
            });
    }

    rechercheButton.addEventListener('click', function() {
        const rechercheQuery = rechercheInput.value.trim();
        fetchResults(rechercheQuery);
    });

    rechercheInput.addEventListener('input', function(event) {
        const rechercheQuery = event.target.value.trim();
        fetchResults(rechercheQuery);
    });

    document.getElementById('resetButton').addEventListener('click', function() {
        rechercheInput.value = '';
        fetchResults();
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchResults();
    });
  </script>
</body>

</html>
