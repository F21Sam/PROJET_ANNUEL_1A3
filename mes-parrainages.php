<?php
include_once('includes/dbpa.php');

?>
<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projet_Annuel_Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="STYLE/nos-animaux.css" rel="stylesheet" />
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/mon-compte.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; 
    $id_user = $_SESSION['id_user'];
    
    $animauxParraine = $dbh->prepare('
        SELECT ANIMAL.id_animal, ANIMAL.race, ANIMAL.name, ANIMAL.sexe, ANIMAL.date_naiss, ANIMAL.lieu_naiss, ANIMAL.caractere, ANIMAL.espece, ANIMAL.histoire, ANIMAL.signes, ANIMAL.photo
        FROM ANIMAL
        INNER JOIN SUBSCRIPTION ON ANIMAL.id_animal = SUBSCRIPTION.id_animal
        WHERE SUBSCRIPTION.id_user = :id_user
    ');
    $animauxParraine->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $animauxParraine->execute();
    $animaux = $animauxParraine->fetchAll(PDO::FETCH_ASSOC);
    ?>
  </header>
  <main>
  <section class="headband">
      <div class="container">
        <h1>Mon profil</h1>
      </div>
    </section>
    <section class="section-1">
    <div class="container-fluid">
        <div class="row">
            <?php include "includes/account_navbar.php"; ?>
            <main class="col-md-9 ml-sm-auto col-lg-10 px-md-4 main-content">
            <h2>Mes parrainages</h2>
            <div class="col-lg-9">
                <div class="container">
                    <div class="grid-container">
                    <?php foreach($animaux as $animal): ?>
                        <?php if ($animal['id_animal'] != 0): ?>
                            <a href="infos-animal.php?id_animal=<?= $animal['id_animal']; ?>">
                                <div class="profil-animal">
                                    <div class="image-container">
                                        <img src="<?= htmlspecialchars($animal['photo']); ?>" class="img-profil-animal">
                                    </div>
                                    <p class="orange"><?= htmlspecialchars($animal['race']); ?></p>
                                    <h2><?= htmlspecialchars($animal['name']); ?></h2>
                                </div>
                            </a>
                        <?php endif; ?>
                    <?php endforeach; ?>
                    </div>    
                    <button class="btn btn-primary d-none d-lg-block btn-orange" onclick="window.location.href='includes/certificat-parrainage.php'">Télécharger mes certificats de parrainage</button> 
                </div>
            </div>
            </main>
        </div>
    </div>
</section>

    <?php include "includes/newsletter_front.php"; ?>
  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <?php include "includes/dark_mode.php"; ?>
</body>
</html>
