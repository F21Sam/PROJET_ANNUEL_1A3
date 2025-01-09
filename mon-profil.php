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
    <?php include "includes/main_navbar.php"; 
    include "includes/dbpa.php"; 

    $id_user = $_SESSION['id_user'];
    
    $userResearch = $dbh->prepare('SELECT nom, prenom, date_naissance, adresse, code_postal, ville, telephone, date_modif FROM USER WHERE id_user = ?');
    $userResearch = $dbh->prepare('SELECT nom, prenom, date_naissance, adresse, code_postal, ville, telephone, signature, date_modif FROM USER WHERE id_user = ?');
    $userResearch->execute([$id_user]);
    $user = $userResearch->fetch(PDO::FETCH_ASSOC);
    
    $peutModif = true;
    if ($user['date_modif']) {
        $lastModif = new DateTime($user['date_modif']);
        $now = new DateTime();
        $interval = $lastModif->diff($now);
        if ($interval->m < 1 && $interval->y == 0) {
            $peutModif = false;
        }
    }
    // Traitement du formulaire des infos utilisateur
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['nom'])) {
        $fieldsToUpdate = [];
        $params = ['id_user' => $id_user];
    
        if (!empty($_POST['nom'])) {
            $fieldsToUpdate[] = 'nom = :nom';
            $params['nom'] = $_POST['nom'];
        }
        if (!empty($_POST['prenom'])) {
            $fieldsToUpdate[] = 'prenom = :prenom';
            $params['prenom'] = $_POST['prenom'];
        }
        if (!empty($_POST['date_naissance'])) {
            $fieldsToUpdate[] = 'date_naissance = :date_naissance';
            $params['date_naissance'] = $_POST['date_naissance'];
        }
        if (!empty($_POST['adresse'])) {
            $fieldsToUpdate[] = 'adresse = :adresse';
            $params['adresse'] = $_POST['adresse'];
        }
        if (!empty($_POST['code_postal'])) {
            $fieldsToUpdate[] = 'code_postal = :code_postal';
            $params['code_postal'] = $_POST['code_postal'];
        }
        if (!empty($_POST['ville'])) {
            $fieldsToUpdate[] = 'ville = :ville';
            $params['ville'] = $_POST['ville'];
        }
        if (!empty($_POST['telephone'])) {
            $fieldsToUpdate[] = 'telephone = :telephone';
            $params['telephone'] = $_POST['telephone'];
        }
    
        if (!empty($fieldsToUpdate)) {
            $fieldsToUpdate[] = 'date_modif = NOW()';
            $majUser = "UPDATE USER SET " . implode(', ', $fieldsToUpdate) . " WHERE id_user = :id_user";
    
            $preparedSql = $dbh->prepare($majUser);
            $preparedSql->execute($params);
    
            header('Location: mon-profil.php');
            exit();
        }
    }
    
    // Enregistrement de la signature
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['signature']) && !empty($_POST['signature'])) {
        $signature = $_POST['signature'];
    
        // Décoder les données de l'image
        list($type, $data) = explode(';', $signature);
        list(, $data) = explode(',', $data);
        $data = base64_decode($data);
    
        // Sauvegarder le fichier
        $signatureNom = 'signature_' . $_SESSION['id_user'] . '.png';
        $signatureChemin = 'IMAGES/signatures/' . $signatureNom;
        file_put_contents($signatureChemin, $data);
    
        // Mettre à jour le chemin de la signature dans la table USER
        $stmt = $dbh->prepare("UPDATE USER SET signature = :signature WHERE id_user = :id_user");
        $stmt->execute(['signature' => $signatureChemin, 'id_user' => $id_user]);
    
        header('Location: ' . $_SERVER['PHP_SELF']);
        exit();
    }
    ?>
  </header>

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
          <h2>Vos informations Personnelles</h2>
          <div class="container">
            <div class="col-lg-5">
              
              <form method="POST" action="">
                <p class="input-title">Nom*</p>
                <input type="text" name="nom" class="form-mail" placeholder="Nom" aria-label="firstname" value="<?= htmlspecialchars($user['nom'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
                <p class="input-title">Prénom*</p>
                <input type="text" name="prenom" class="form-mail" placeholder="Prénom" aria-label="name" value="<?= htmlspecialchars($user['prenom'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
              
                <p class="input-title">Date de naissance</p>
                <input type="date" name="date_naissance" class="form-mail" placeholder="Date de naissance" aria-label="birthday" value="<?= htmlspecialchars($user['date_naissance'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
                <p class="input-title">Adresse</p>
                <input type="text" name="adresse" class="form-mail" placeholder="Adresse" aria-label="address" value="<?= htmlspecialchars($user['adresse'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
                <p class="input-title">Code Postal</p>
                <input type="text" name="code_postal" class="form-mail" placeholder="Code postal" aria-label="postal-code" value="<?= htmlspecialchars($user['code_postal'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
                <p class="input-title">Ville</p>
                <input type="text" name="ville" class="form-mail" placeholder="Ville" aria-label="city" value="<?= htmlspecialchars($user['ville'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
              
                <p class="input-title">Numéro de téléphone</p>
                <input type="text" name="telephone" class="form-mail" placeholder="Numéro de téléphone" aria-label="phone-number" value="<?= htmlspecialchars($user['telephone'] ?? ''); ?>" <?= !$peutModif ? 'disabled' : ''; ?>>
                <div class="button-container">
                  <?php if ($peutModif): ?>
                  <button class="btn send-mail-btn btn-primary btn-orange" type="submit">Envoyer</button>
                  <?php endif; ?>  
                  <?php if (!$peutModif): ?>
                <div class="alert alert-warning" role="alert">
                  Vous avez déjà modifié vos informations ce mois-ci. Vous pourrez les modifier à nouveau après un mois.
                </div>
              <?php endif; ?>  
                </div>
              </form>
              <div class="button-container">
                <button class="btn btn-primary d-lg-block btn-orange" onclick="window.location.href='includes/userPDF.php'">Télécharger mes données personnelles</button> 
              </div>
              <br>
            </div>
          </div>
        
          <div class="container">
            <h2 class="">Signature</h2>
              <div class="col-lg-3">
                <?php if ($user['signature']): ?>
                  <p class="input-title">Signature actuelle</p>
                  <img src="<?= htmlspecialchars($user['signature']); ?>" alt="Signature" style="max-width: 100%; height: auto;">
                <?php endif; ?>
              </div>
              <div class="col-lg-3">
                <p class="input-title">Ajouter/Modifier Signature</p>
                <form method="POST" id="signature-form">
                  <canvas id="signature-pad" style="border:1px solid #000; width: 100%; height: 200px;"></canvas>
                  <br>
                  <button id="effacer" class="btn btn-warning btn-orange btn-primary" type="button">Effacer</button>
                  <button id="sauvegarder" class="btn btn-success btn-orange btn-primary" type="button">Enregistrer</button>
                  <input type="hidden" name="signature" id="signature">
                </form>
              </div>
              
            </div>
          </main>
      </div>
    </div>
  </section>
  
  <?php include "includes/newsletter_front.php"; ?>
  
  <footer>
    <?php include "includes/footer_front.php"; ?>
  </footer>
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <?php include "includes/dark_mode.php"; ?>
  <script>
    document.addEventListener('DOMContentLoaded', (event) => {
      // Récupération du canevas et du contexte de dessin
      const canvas = document.getElementById('signature-pad');
      const ctx = canvas.getContext('2d');
      console.log('Canvas loaded:', canvas);

      // Ajustement des dimensions du canevas
      canvas.width = canvas.offsetWidth;
      canvas.height = canvas.offsetHeight;

      let drawing = false;

      // Fonction pour démarrer le dessin
      function startDrawing(event) {
          drawing = true;
          ctx.beginPath(); // Commencer un nouveau chemin de dessin
          draw(event); // Commencer à dessiner immédiatement
      }

      // Fonction pour arrêter le dessin
      function stopDrawing() {
          drawing = false;
          ctx.closePath(); // Fermer le chemin de dessin
      }

      // Fonction pour dessiner
      function draw(event) {
          if (!drawing) return;

          event.preventDefault(); // Empêcher le comportement par défaut (défilement)

          let x, y;
          if (event.touches) {
              // Événements tactiles
              const touch = event.touches[0];
              const rect = canvas.getBoundingClientRect();
              x = touch.clientX - rect.left;
              y = touch.clientY - rect.top;
          } else {
              // Événements de la souris
              const rect = canvas.getBoundingClientRect();
              x = event.clientX - rect.left;
              y = event.clientY - rect.top;
          }

          ctx.lineWidth = 2;
          ctx.lineCap = 'round';
          ctx.strokeStyle = 'black';

          ctx.lineTo(x, y); // Ajouter un point au chemin
          ctx.stroke(); // Dessiner le chemin
          ctx.beginPath(); // Commencer un nouveau chemin pour éviter de relier des segments non connectés
          ctx.moveTo(x, y); // Déplacer le point de départ du nouveau chemin
      }

      // Événements de la souris pour dessiner
      canvas.addEventListener('mousedown', startDrawing);
      canvas.addEventListener('mousemove', draw);
      canvas.addEventListener('mouseup', stopDrawing);
      canvas.addEventListener('mouseout', stopDrawing);

      // Événements tactiles pour dessiner
      canvas.addEventListener('touchstart', startDrawing);
      canvas.addEventListener('touchmove', draw);
      canvas.addEventListener('touchend', stopDrawing);
      canvas.addEventListener('touchcancel', stopDrawing);

      // Fonction pour effacer le canevas
      document.getElementById('effacer').addEventListener('click', function() {
          ctx.clearRect(0, 0, canvas.width, canvas.height);
      });

      // Fonction pour sauvegarder le canevas
      document.getElementById('sauvegarder').addEventListener('click', function() {
          const signatureInput = document.getElementById('signature');
          const dataURL = canvas.toDataURL('image/png');
          signatureInput.value = dataURL;

          console.log('Saving signature:', signatureInput.value);

          // Soumettre le formulaire contenant l'input hidden
          document.getElementById('signature-form').submit();
      });
    });
  </script>
</body>
</html>