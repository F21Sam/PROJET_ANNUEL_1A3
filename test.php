<?php include_once('includes/journalisations.php'); ?>
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
  
  <link rel="icon" href="IMAGES/logo-icon.png" type="image/png">

    <style>
        /* Light Mode Styles */
        body {
        background-color: white;
        color: black;
        }

        header, footer {
        background-color: lightgray;
        }

        button {
        background-color: blue;
        color: white;
        }

        .section-1 {
        background-color: #F7E3D3;
        padding: 30px;
        }

        .section-2 {
        background-color: #F7E3D3;
        padding: 70px 0;
        }

        .section-3 {
        background-color: #BC644B;
        padding: 50px;
        color: white;
        }

        .section-3 .p-title {
        font-size: medium;
        color: white;
        }

        .section-3 p {
        font-size: medium;
        color: black;
        }

        .orange {
        color: #BC644B;
        }

        .vert {
        color: #183425;
        }
        

    </style>

</head>

<body>
  <header>
    <?php include "includes/main_navbar.php"; ?>
  </header>
  <main>
    
    <section class="headband">
      <div class="container">
        <h1>Trouvez votre ami à quatre pattes,<br>parrainez chez Amimal !</h1>
        <button id="toggle-dark-mode">Toggle Dark Mode</button>
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
            <a href="#" class="btn btn-primary savoir-plus-btn">En savoir plus<img src="IMAGES/right-arrow.png" class="btn-icon"></a>
          </div>

        </div>
      </div>
    </section>

    <section class="section-2">
      <div class="container">
        <div class="row">
          
          <div class="col-sm-3 custom-column">
            <p>7</p>
            <p>hectares</p>
          </div>

          <div class="col-sm-3 custom-column">
            <p>15</p>
            <p>enclos</p>
          </div>

          <div class="col-sm-3 custom-column">
            <p>46</p>
            <p>animaux sauvés</p>
          </div>

          <div class="col-sm-3 custom-column">
            <p>9</p>
            <p>soigneurs</p>
          </div>

        </div>
      </div>
    </section>

  </main>
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const toggleButton = document.getElementById('toggle-dark-mode');

      // Function to apply dark mode based on localStorage
      const applyDarkMode = () => {
        if (localStorage.getItem('darkMode') === 'enabled') {
          document.body.classList.add('dark-mode');
        } else {
          document.body.classList.remove('dark-mode');
        }
      };

      // Toggle dark mode on button click
      toggleButton.addEventListener('click', () => {
        if (localStorage.getItem('darkMode') !== 'enabled') {
          localStorage.setItem('darkMode', 'enabled');
        } else {
          localStorage.setItem('darkMode', 'disabled');
        }
        applyDarkMode();
      });

      // Apply dark mode on page load based on localStorage
      applyDarkMode();
    });
  </script>
</body>

</html>

