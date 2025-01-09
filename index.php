<?php include_once('includes/journalisations.php');?>
<!doctype html>
<html lang="fr">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Amimal</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  
  <link href="STYLE/style_global.css" rel="stylesheet" />
  <link href="STYLE/index.css" rel="stylesheet" />
  <link href="STYLE/dark_mode.css" rel="stylesheet" />
  <link href="STYLE/newsletter&footer.css" rel="stylesheet" />
  <link rel="icon" href="IMAGES/logo-icon.png" type="image/png">
</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; ?>
  </header>
  <main>
    
    <section class="headband">
      <div class="container">
        <h1>Trouvez votre ami à quatre pattes,<br>parrainez chez Amimal !</h1>
      </div>
    </section>

    <section class="section-1">
      <div class="container">
        <div class="row">

          <div class="col-12 col-lg-7 custom-img">
            <img src="IMAGES/panda.png" alt="panda" class="img-fluid" width="80%" height="80%" />
          </div>

          <div class="col-12 col-lg-5 custom-text">
            <h2 class="orange">Bienvenue chez Amimal !</h2>
            <p>Amimal est bien plus qu'un refuge : c’est un nouveau départ pour nos animaux issus de sauvetages.</p>
            <p>Parcourez nos résidents à la recherche de votre prochain compagnon de vie ou simplement laissez vous inspirer par leurs histoires émouvantes. Chez Amimal, chaque acte de parrainage compte et fait une réelle différence dans la vie des animaux que nous chérissons. Rejoignez nous dès aujourd'hui pour donner de l'amour et de l'espoir à nos amis à quatre pattes !</p>
            
            <div class="button-container">
              <a href="le-refuge.php" class="btn btn-primary savoir-plus-btn btn-orange">En savoir plus<img src="IMAGES/right-arrow.png" class="btn-icon"></a>
            </div>
          </div>

        </div>
    </section>

    <section class="section-2">
      <div class="container">
        <div class="row">
          
          <div class="col-sm-3 custom-column p-title orange">
            <p>7</p>
            <p>hectares</p>
          </div>

          <div class="col-sm-3 custom-column p-title orange">
            <p>15</p>
            <p>enclos</p>
          </div>

          <div class="col-sm-3 custom-column p-title orange">
            <p>46</p>
            <p>animaux sauvés</p>
          </div>

          <div class="col-sm-3 custom-column p-title orange">
            <p>9</p>
            <p>soigneurs</p>
          </div>

        </div>
      </div>
    </section>

    <section class="section-3">
      <div class="container">
      <h2>Devenez parrain en 3 étapes</h2>
        <div class="row">

          <div class="col mt-5 custom-column">
            
            
            <!--<img class="circle-number" src="IMAGES/circle1.png" height="24px" width="24px">-->
              
            <p class="p-title">Choisissez votre filleul</p>
            <p>Parcourez nos résidents à la recherche de l’animal qui touche votre coeur.</p>
          </div>

          <div class="col mt-5 custom-column">
            <p class="p-title">Choisissez votre formule</p>
            <p>Chaque formule à des prix et contreparties différents.</p>
          </div>

          <div class="col mt-5 custom-column">
            <p class="p-title">Devenez parrain pendant 1 an</p>
            <p>Vous êtes parrain pour une durée de 1 vous recevez vos contreparties à l’effigie de votre filleul.</p>
          </div>

        </div>
      </div>

      <div class="button-container">
        <a href="pourquoi-parrainer.php" class="btn btn-vert">En savoir plus<img src="IMAGES/right-arrow.png" class="btn-icon"></a>
      </div>
    </section>

    <section class="section-4">
      
    </section>

    <?php include "includes/newsletter_front.php"; ?>
    
    
  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>

  <?php include "includes/dark_mode.php"; ?>
  
</body>

</html>