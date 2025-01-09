<?php 
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('includes/dbpa.php'); 

$animauxBdd = $dbh->prepare('SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL');
$animauxBdd->execute();
$animaux = $animauxBdd->fetchAll(PDO::FETCH_ASSOC);

$queryRaces = $dbh->prepare('SELECT DISTINCT race FROM ANIMAL');
$queryRaces->execute();
$races = $queryRaces->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projet_Annuel_Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/nos-animaux.css" rel="stylesheet" />
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
        <h1>Nos animaux</h1>
      </div>
    </section>

    <section class="section-1">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                
                <div class="search-column">
                    <h3 class="titre-colone-search">Rechercher</h3>
                    
                    <div class="input-group">
                        <input type="text" class="input-text" placeholder="Recherchez un animal" id="rechercheInput" aria-label="mail">
                        
                        <div class="input-group-append">
                            <button class="btn send-input-btn btn-primary" type="button" id="rechercheButton">Envoyer</button>
                        </div>
                    </div>

                    <h5 class="soustitre-colone-recherche">Sa race</h5>

                    <div class="dropdown-container">
                        <select class="species-selector" id="race" name="race">
                            <option value="all">Toutes</option>
                            <?php foreach ($races as $race): ?>
                                <option value="<?= htmlspecialchars($race['race']) ?>"><?= htmlspecialchars($race['race']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn reset-btn btn-primary btn-orange" type="button" id="resetButton">Réinitialiser</button>
                </div>


            </div>
            <div class="col-lg-9">
                <div class="container">
                    <div class="grid-container" id="results">
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
    const raceSelect = document.getElementById('race');
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
            const animalDiv = document.createElement('div');
            animalDiv.className = 'profil-animal';

            const link = document.createElement('a');
            link.href = `infos-animal.php?id_animal=${item.id_animal}`;

            const imgDiv = document.createElement('div');
            imgDiv.className = 'image-container';

            const img = document.createElement('img');
            img.src = item.photo;
            img.className = 'img-profil-animal';
            imgDiv.appendChild(img);

            const raceP = document.createElement('p');
            raceP.className = 'orange';
            raceP.textContent = item.race;

            const nameH2 = document.createElement('h2');
            nameH2.textContent = item.name;

            link.appendChild(imgDiv);
            link.appendChild(raceP);
            link.appendChild(nameH2);
            animalDiv.appendChild(link);

            resultsDiv.appendChild(animalDiv);
        });
    }

    function fetchResults(animauxBdd = '', race = 'all') {
        fetch(`includes/recherche_animal.php?recherche=${encodeURIComponent(animauxBdd)}&race=${encodeURIComponent(race)}`)
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
        const race = raceSelect.value;
        fetchResults(rechercheQuery, race);
    });

    rechercheInput.addEventListener('input', function(event) {
        const rechercheQuery = event.target.value.trim();
        const race = raceSelect.value;
        fetchResults(rechercheQuery, race);
    });

    raceSelect.addEventListener('change', function() {
        const rechercheQuery = rechercheInput.value.trim();
        const race = raceSelect.value;
        fetchResults(rechercheQuery, race);
    });

    document.getElementById('resetButton').addEventListener('click', function() {
        rechercheInput.value = '';
        raceSelect.value = 'all';
        fetchResults();
    });

    document.addEventListener('DOMContentLoaded', () => {
        fetchResults();
    });
  </script>
</body>

</html>
