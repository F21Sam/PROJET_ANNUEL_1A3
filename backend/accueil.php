<?php 
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
global $dbh;
require_once ("includes/dbpa.php");
include_once("includes/header.php");
include_once("includes/sidebar.php");
include_once('includes/journalisations.php');

//recuperation des donnees dynamiques

  $stmt = $dbh->query('SELECT id_animal  FROM ANIMAL');
  $id_animal = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalAnimaux = count($id_animal);

  $stmt = $dbh->query('SELECT id_animal  FROM SUBSCRIPTION');
  $id_animal = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalAnimauxP = count($id_animal);

  $stmt = $dbh->query('SELECT id_topic, author FROM TOPIC');
  $topics = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalTopic = count($topics);
  $authors = array_column($topics, 'author');
  $totalAuthor = count(array_unique($authors));

  $stmt = $dbh->query('SELECT id_product  FROM PRODUCT');
  $id_product = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalProduct = count($id_product);

  $stmt = $dbh->query('SELECT id_product  FROM PRODUCT where promotion = 1');
  $id_product = $stmt->fetchAll(PDO::FETCH_ASSOC);
  $totalProductProm = count($id_product);


?>

  <body class="g-sidenav-show bg-gray-100">

    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg">
      <?php include_once("includes/navbar.php"); ?>
      <div id="dropdown-container"></div>


<script>
  // Script pour inclure conditionnellement le menu déroulant
    document.addEventListener('DOMContentLoaded', function() {
        function loadDropdownMenu() {
            console.log('Vérification de la largeur de la fenêtre :', window.innerWidth);
            if (window.innerWidth <= 755) {
                console.log('Chargement du menu déroulant...');
                fetch('includes/sidenav.php')
                    .then(response => response.text())
                    .then(data => {
                        document.getElementById('dropdown-container').innerHTML = data;
                        console.log('Menu déroulant chargé avec succès.');
                    })
                    .catch(error => console.error('Erreur lors du chargement du menu déroulant :', error));
            } else {
                console.log('La largeur de la fenêtre est supérieure à 768px, le menu déroulant ne sera pas chargé.');
            }
        }

        // Charger le menu déroulant au chargement initial
        loadDropdownMenu();

        // Recharger le menu déroulant lors du redimensionnement de la fenêtre
        window.addEventListener('resize', loadDropdownMenu);
    });
</script>
<div class="container">
  <div class="container-fluid py-0">
  <div class="col-12">
    <div class="row">
      <div class="col-lg-12 position-relative z-index-2">
        <div class="card card-plain mb-4">
          <div class="card-body p-0">
            <div class="row">
              <div class="col-lg-6">
                <div class="d-flex flex-column h-100">
                  <h2 class="font-weight-bolder mb-0"> Statistics generales</h2>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-lg-6 col-sm-5">
            <div class="card  mb-3">
              <div class="card">
                <div class="card-body">
                  <div class="ms-md-auto  d-flex align-items-center"> 
                  <i class="fas fa-dog card-icon mx-2 mb-2"></i>
                  <h5 class="card-title">Gestion des animaux</h5>
                  </div>
                  <p class="card-text">Nombre d'animaux :<?php echo $totalAnimaux; ?></p>
                  <p class="card-text">Nombre d'animaux parrainés :<?php echo $totalAnimauxP; ?></p>
                  <a href="gestion_animaux.php" class="btn btn-primary">Gérer</a>
                </div>
              </div>
            </div>

            <div class="card  mb-3">
              <div class="card">
                <div class="card-body">
                <div class="ms-md-auto  d-flex align-items-center"> 
                  <i class="fas fa-newspaper card-icon mx-2 mb-2"></i>
                  <h5 class="card-title">Un Refuge, Une Famille</h5>
                </div>
                  <p class="card-text">Au cœur de nos convictions pour la protection animale...</p>
                  <p class="card-text">Date de publication : 21 Avril 2024</p>
                  <a href="gestion_articles.php" class="btn btn-primary">Voir l'article</a>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-6 col-sm-5 mt-sm-0 mt-4">
            <div class="card  mb-3">
              <div class="card">
              <div class="card-body">
              <div class="ms-md-auto  d-flex align-items-center"> 
                <i class="fas fa-user card-icon mx-2 mb-2"></i>
                  <h5 class="card-title">Moderation du Forum</h5>
              </div>
                  <p class="card-text">Nombre de sujets Abordés : <?php echo $totalTopic; ?> </p>
                  <p class="card-text">Nombre d'auteurs: <?php echo $totalAuthor; ?> </p>
                  <a href="moderation_forum.php" class="btn btn-primary">Moderer</a>
                </div>
              </div>
            </div>

            <div class="card ">
              <div class="card">
                <div class="card-body">
                <div class="ms-md-auto  d-flex align-items-center">
                  <i class="fas fa-calendar card-icon mx-2 mb-2"></i>
                  <h5 class="card-title">Stock de la boutique</h5>
                </div>
                  <p class="card-text">Nombre de produits: <?php echo $totalProduct; ?></p>
                  <p class="card-text">Nombre de produits en promotion :<?php echo $totalProductProm; ?></p>
                  <a href="stock.php" class="btn btn-primary">Voir le stock</a>
                </div>
              </div>
            </div>
          </div>
        </div>
        
        
        

        
        <?php


$stmt = $dbh->query('SHOW TABLES');
$tables = $stmt->fetchAll(PDO::FETCH_ASSOC);


?>

<div class="col-lg-12 col-sm-5 mt-sm-0 mt-4">
<div class="card">
<div class="card-header">
<h4>Gestion de la base de données</h4>
</div>
<div class="card-body d-flex flex-wrap justify-content-between">
<div class="card mb-3 col-lg-3">
  <div class="card-header">
    <h5 class="card-title">Ajouter une nouvelle table</h5>
  </div>
  <div class="card-body">
    <form action="includes/create_table.php" method="post">
      <div class="mb-1">
        <label for="nom_table" class="form-label"></label>
        <input type="text" class="form-control" id="nom_table" name="nom_table" placeholder="Nom de la table">
      </div>
      <div class="mb-3">
        <label for="nom_colonne" class="form-label"></label>
        <input type="text" class="form-control" id="nom_colonne" name="nom_colonne[]" placeholder="Nom de la colonne">
      </div>
      <div class="mb-1">
        <label for="type_colonne" class="form-label">Type de la colonne</label>
        <select class="form-control" id="type_colonne" name="type_colonne[]">
          <option value="INT">INT</option>
          <option value="VARCHAR">CHAR</option>
          <option value="VARCHAR">VARCHAR</option>
          <option value="VARCHAR">boolean</option>
        </select>
      </div>
      <button type="submit" class="btn btn-primary mt-2">Ajouter la table</button>
    </form>
  </div>
</div>

<?php foreach ($tables as $table) : ?>
  <div class="card mb-3 col-lg-3">
    <div class="card-header">
      <h5 class="card-title"><?php echo htmlspecialchars($table['Tables_in_projet']); ?></h5>
      <div class="dropdown float-end">
        <button class="btn btn-primary dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
          Options
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <li><a class="dropdown-item" href="gestion_bdd.php?table=<?php echo htmlspecialchars($table['Tables_in_projet']); ?>">Modifier</a></li>
          <li><a class="dropdown-item" href="includes/drop_table.php?table=<?php echo htmlspecialchars($table['Tables_in_projet']); ?>">Supprimer</a></li>
        </ul>
      </div>
    </div>
    <div class="card-body">
      <ul>
        <?php
        $stmt = $dbh->query("DESCRIBE " . $table['Tables_in_projet']);
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($columns as $column) {
          echo '<li>' . htmlspecialchars($column['Field']) . '</li>';
        }
        ?>
      </ul>
    </div>
  </div>
<?php endforeach; ?>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<?php include_once("includes/footer.php"); ?>







