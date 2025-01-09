<?php
session_start();
if(!isset($_SESSION['pseudo']) AND !isset($_SESSION['id_user'])){
  header('Location: login_page_forum.php');
}
require('forum_publication.php');
include 'includes/header.php';

$requeteAnimaux = $dbh->query("SELECT id_animal, name FROM ANIMAL");
$animaux = $requeteAnimaux->fetchAll(PDO::FETCH_ASSOC);
?> 

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Publication sujet forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style_2.css" rel="stylesheet" />
    <link href="STYLE/style_global.css" rel="stylesheet" />
</head>
<header>
    <?php include "includes/forum_navbar.php"; ?>
  </header>
<body style="padding-top: 6rem;">
  <div class="container mt-5">
    <form method="POST">
      <?php if(isset($errorMsg)): ?>
        <div class="alert alert-danger" role="alert">
          <?= $errorMsg; ?>
        </div>
      <?php elseif(isset($successMsg)): ?>
        <div class="alert alert-success" role="alert">
          <?= $successMsg; ?>
        </div>
      <?php endif; ?>
      
      <div class="mb-3">
        <label for="title" class="form-label">Titre du sujet</label>
        <input type="text" class="form-control" id="title" name="title" aria-describedby="title">
      </div>
      
      <div class="mb-3">
        <label for="id_animal">Sélectionner un animal :</label>
        <select name="id_animal" id="id_animal">
            <?php foreach ($animaux as $animal): ?>
                <option value="<?php echo $animal['id_animal']; ?>"><?php echo $animal['name']; ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      
      <div class="mb-3">
        <label for="content" class="form-label">Contenu du sujet</label>
        <textarea class="form-control" id="content" name="content" rows="3"></textarea>
      </div>
      
      <button type="submit" name="publish" class="btn btn-primary">Publier</button>
    </form>
  </div>
</body>
</html>
