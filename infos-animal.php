<?php 

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/dbpa.php');

if (isset($_GET['id_animal']) && !empty($_GET['id_animal'])) {
    $animal_id = $_GET['id_animal'];

    $rechercheAnimal = $dbh->prepare('SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE id_animal = ?');
    $rechercheAnimal->execute([$animal_id]);
    $animal = $rechercheAnimal->fetch(PDO::FETCH_ASSOC);

    if (!$animal) {
        header('Location: nos-animaux.php');
        exit();
    }
} else {
    header('Location: nos-animaux.php');
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
    <?php include "includes/main_navbar.php"; 
    $form_action = '';
    if (isset($_SESSION['pseudo']) && isset($_SESSION['id_user'])) {
        $form_action = 'includes/parrainage.php?id_animal=' . $animal_id;
    } else {
        $form_action = 'login_page.php';
    }
    $boolParraine = false;
    if (isset($_SESSION['id_user'])) {
        $user_id = $_SESSION['id_user'];
        $checkParrainage = $dbh->prepare('SELECT COUNT(*) FROM SUBSCRIPTION WHERE id_user = ? AND id_animal = ?');
        $checkParrainage->execute([$user_id, $animal_id]);
        $boolParraine = $checkParrainage->fetchColumn() > 0;
    }
    ?>
  </header>
  <main>

    <section class="section-1">
      <div class="container">
        <div class="row">

          <div class="col-12 col-lg-6 custom-img">
            <div class="image-container">
              <img src="<?= htmlspecialchars($animal['photo']); ?>" alt="<?= htmlspecialchars($animal['name']); ?>" class="img-fluid" width="80%" height="80%" />
            </div>
          </div>

          <div class="col-12 col-lg-5 custom-text info">
            <a href="nos-animaux.php" class="lien-back"><  Tous nos animaux</a>
            <p class="espece-p"><?= htmlspecialchars($animal['race']); ?></p>
            <h2 class="orange"><?= htmlspecialchars($animal['name']); ?></h2>

            <p>Sexe : <?= htmlspecialchars($animal['sexe']); ?></p>
            <p>Naissance le : <?= htmlspecialchars($animal['date_naiss']); ?></p>
            <p>Naissance à : <?= htmlspecialchars($animal['lieu_naiss']); ?></p>
            <p>Caractère : <?= htmlspecialchars($animal['caractere']); ?></p>
            <p>Prix du parrainage : 9.99 euros</p>

            <?php if (!$boolParraine): ?>
            <form action="<?php echo $form_action; ?>" method="POST">
                <button type="submit" class="btn btn-primary savoir-plus-btn btn-orange">
                  Parrainer <?= htmlspecialchars($animal['name']); ?>
                  <img src="IMAGES/right-arrow.png" class="btn-icon">
                </button>
            </form>
            <?php else: ?>
                <p class="orange">Vous parrainez déjà cet animal.</p>
            <?php endif; ?>
          </div>

        </div>
    </section>

    <section class="section-2">
      <div class="container">
        <div class="row">
          
          <h2 class="titre vert">Description complète</h2>

          <h4 class="titre-h4">Son espèce</h4>
          <p><?= htmlspecialchars($animal['espece']); ?></p>

          <h4 class="titre-h4">Son histoire</h4>
          <p><?= htmlspecialchars($animal['histoire']); ?></p>
          
          <h4 class="titre-h4">Ses signes distinctifs</h4>
          <p><?= htmlspecialchars($animal['signes']); ?></p>
          
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
