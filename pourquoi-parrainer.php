<?php include_once('includes/journalisations.php'); 

?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Projet_Annuel_Accueil</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/pourquoi-parrainer.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; ?>
  </header>
  <main>
    
    <section class="headband">
      <div class="container">
        <h1>Pourquoi parrainer ?</h1>
      </div>
    </section>

    <section class="section-1">
      <div class="container">
        <div class="row">

          <div class="col-12 col-lg-7">
            <h3 class="titre orange">Pourquoi Parrainer un Amimal ?</h3>
            <p>Au cœur de nos convictions pour la protection animale réside le Refuge Amimal, bien plus qu'un simple lieu de transit, c'est un foyer chaleureux où chaque être à quatre pattes trouve amour, soins, et espoir.</p>
            
            <h3 class="titre orange">Qui Sommes-Nous ?</h2>
            <p>Amimal n'est pas juste une association, c'est le fruit de l'engagement passionné d'Ana, Fanta et Quentin, trois âmes animées par un amour inconditionnel pour nos amis à fourrure, à plumes et à écailles. Étudiants, certes, mais surtout des défenseurs déterminés à offrir une voix à ceux qui n'en ont pas.</p>
            
            <h3 class="titre orange">Notre Combat</h2>
            <p>Au quotidien, nous ouvrons nos portes à ceux qui en ont besoin, que ce soit pour échapper à la rue, guérir de blessures physiques ou émotionnelles, ou simplement retrouver le bonheur d'être aimé. Notre combat ne se limite pas à sauver des vies, mais à leur donner une seconde chance, à leur redonner dignité et confiance en l'humain.</p>
          </div>

          <div class="col-12 col-lg-5 ">
            <img src="IMAGES/panda.png" alt="loutre" class="img-fluid" width="150%" height="150%" />
          </div>

          <h3 class="titre orange">L'Action Amimal</h2>
          <p>Le Refuge Amimal est bien plus qu'un abri. C'est un foyer où chaque miaulement, aboiement ou gazouillis est accueilli avec amour et compréhension. Nous ne comptons pas les heures passées à panser les blessures, à réconforter les cœurs brisés, et à tisser des liens indéfectibles entre nos résidents et nos bénévoles.</p>
          <p>Chaque animal qui franchit nos portes devient une partie de notre famille, et nous œuvrons sans relâche pour leur trouver le foyer aimant qu'ils méritent.</p>

          <h3 class="titre orange">Comment Vous Pouvez Aider ?</h2>
          <p>Vous êtes notre force motrice, notre rayon de soleil dans les moments sombres. Votre soutien, qu'il soit financier, moral, ou simplement par le partage de notre mission, nous permet de continuer à faire la différence dans la vie de ces êtres vulnérables.</p>

          <h3 class="titre orange">Ensemble, Faisons la Différence</h2>
          <p>Au Refuge Amimal, chaque histoire compte. Chaque vie sauvée, chaque cœur guéri, renforce notre détermination à bâtir un monde où chaque animal est traité avec respect et compassion. Rejoignez-nous dans notre combat, et ensemble, faisons une différence, une patte à la fois.</p>

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