<?php include_once('includes/journalisations.php');?>
<!doctype html>
<html lang="fr">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projet_Annuel_Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_2.css" rel="stylesheet" />
    <link href="STYLE/style_global.css" rel="stylesheet" />
</head>
  <body>
    <header>
      <?php include "includes/navbar_front.php"; ?>
    </header>
    <main>
      <section class="section-1">
        <div class="container d-flex align-items-center justify-content-center fs-1 text-white flex-column">
          <h1>Trouvez votre ami à quatre pattes,</h1>
          <h1>parrainez chez Amimal !</h1>
        </div>
      </section>
      
      <section class="section-2">
        <div class="container py-5">
          <div class="row">
            <div class="col-12 col-lg-7">
              <img src="IMAGES/panda.png" alt="Panda" class="img-fluid" width="80%" height="80%"/>
            </div>
            <div class="col-12 col-lg-4 d-flex align-items-center justify-content-center flex-column">
              <h2 class="titre-orange">Bienvenue chez Amimal !</h2>
              <p>Amimal est bien plus qu'un refuge : c’est un nouveau départ pour nos animaux issus de sauvetages.</p>
              <p>Parcourez nos résidents à la recherche de votre prochain compagnon de vie ou simplement laissez vous inspirer par leurs histoires émouvantes. Chez Amimal, chaque acte de parrainage compte et fait une réelle différence dans la vie des animaux que nous chérissons. Rejoignez nous dès aujourd'hui pour donner de l'amour et de l'espoir à nos amis à quatre pattes !</p>
              <a href="#" class="btn btn-primary mx-lg-2">En savoir plus <svg src="IMAGES/arrow-right-circle.svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
              </svg></a>
            </div>
            <div class="col d-flex align-items-center justify-content-center flex-column">
              <img src="IMAGES/feuille-verteClair.png" alt="feuille verte claire" width="200" height="200" class="feuille-verte">
            </div> 
            <div class="row">
            <div class="col d-flex align-items-center justify-content-center fs-1 flex-column">
                <p>7</p>
                <p class="fs-3">hectares</p>
                </div>
             <div class="col d-flex align-items-center justify-content-center fs-1 flex-column">
                <p>15</p>
                <p class="fs-3">enclos</p>
             </div>    
             <div class="col d-flex align-items-center justify-content-center fs-1 flex-column">
              <p>46</p>
              <p class="fs-3">animaux sauvés</p>            
            </div> 
            <div class="col d-flex align-items-center justify-content-center fs-1 flex-column">
              <p>9</p>
              <p class="fs-3">soigneurs</p>            
            </div> 
          </div>
        </div>
      </section>
      <section class="section-3">
        <div class="container py-2">
        <div class="row">
          <h2 class="t-white mt-5">Devenez parrain en 3 étapes</h2>
          <div class="col mt-5">
            <h5 class="t-white"><svg src="IMAGES/1-circle-fill.svg" width="32" height="32" fill="#183425" class="bi bi-1-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M9.283 4.002H7.971L6.072 5.385v1.271l1.834-1.318h.065V12h1.312z"/>
            </svg> Choisissez votre filleul</h5>
            <p>Parcourez nos résidents à la recherche de l’animal qui touche votre coeur.</p>
          </div>
          <div class="col mt-5">
            <h5 class="t-white"><svg src="IMAGES/2-circle-fill.svg" width="32" height="32" fill="#183425" class="bi bi-2-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.646 6.24c0-.691.493-1.306 1.336-1.306.756 0 1.313.492 1.313 1.236 0 .697-.469 1.23-.902 1.705l-2.971 3.293V12h5.344v-1.107H7.268v-.077l1.974-2.22.096-.107c.688-.763 1.287-1.428 1.287-2.43 0-1.266-1.031-2.215-2.613-2.215-1.758 0-2.637 1.19-2.637 2.402v.065h1.271v-.07Z"/>
            </svg> Choisissez votre formule</h5>
            <p>Chaque formule à des prix et contreparties différents.</p>
          </div>
          <div class="col mt-5">
            <h5 class="t-white"><svg src="IMAGES/3-circle-fill.svg" width="32" height="32" fill="#183425" class="bi bi-3-circle-fill" viewBox="0 0 16 16">
              <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0m-8.082.414c.92 0 1.535.54 1.541 1.318.012.791-.615 1.36-1.588 1.354-.861-.006-1.482-.469-1.54-1.066H5.104c.047 1.177 1.05 2.144 2.754 2.144 1.653 0 2.954-.937 2.93-2.396-.023-1.278-1.031-1.846-1.734-1.916v-.07c.597-.1 1.505-.739 1.482-1.876-.03-1.177-1.043-2.074-2.637-2.062-1.675.006-2.59.984-2.625 2.12h1.248c.036-.556.557-1.054 1.348-1.054.785 0 1.348.486 1.348 1.195.006.715-.563 1.237-1.342 1.237h-.838v1.072h.879Z"/>
            </svg> Devenez parrain pendant 1 an</h5>
            <p>Vous êtes parrain pour une durée de 1 vous recevez vos contreparties à l’effigie de votre filleul.</p>
          </div>
          </div>
          <div class="col mt-5 d-flex align-items-center justify-content-center flex-column">
            <a href="#" class="btn btn-primary-green mx-lg-2">En savoir plus <svg src="IMAGES/arrow-right-circle.svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
              <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
            </svg></a>
         </div>
        </div>
      </section>
      <section class="section-4">
        <div class="container d-flex justify-content-center fs-1 text-white flex-column">
          <h1>Découvrez l’histoire de Nala <br> <a href="#" class="btn mt-3 btn-primary">Découvrir <svg src="IMAGES/arrow-right-circle.svg" width="25" height="25" fill="currentColor" class="bi bi-arrow-right-circle" viewBox="0 0 16 16">
            <path fill-rule="evenodd" d="M1 8a7 7 0 1 0 14 0A7 7 0 0 0 1 8m15 0A8 8 0 1 1 0 8a8 8 0 0 1 16 0M4.5 7.5a.5.5 0 0 0 0 1h5.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3a.5.5 0 0 0 0-.708l-3-3a.5.5 0 1 0-.708.708L10.293 7.5z"/>
          </svg></a></h1>
        </div>
      </section>
      <section class="section-5">
        <div class="container-fluid section-5-bot">
          <div class="row ">
            <div class="col d-flex align-items-center justify-content-center flex-column">
              <h2 class="titre-orange">Notre Newsletter</h2>
              <p class="mt-2">Nouveaux arrivants, vidéos adorablement mignonnes, naissances, acualités sur le refuge Amimal, suivez-nous !</p>
              <div class="col-12">
                <div class="form-check">
                  <input class="form-check-input" type="checkbox" id="gridCheck">
                  <label class="form-check-label" for="gridCheck">
                    <p class="RGPD"> cochant cette case vous acceptez le conditions relatives notre politique de confidentialité RGPD</p>
                  </label>
                </div>
                <input type="text" class="form-mail" placeholder="Saisissez votre adresse mail " aria-label="Mail">
              </div>
            </div>
            <div class="col-12 col-lg-5 mr-5 float-end">
              <img src="IMAGES/loutre.png" alt="loutre" class="img-fluid" width="150%" height="150%"/>
            </div>
        </div>
      </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  </main>
  </body>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>
</html>