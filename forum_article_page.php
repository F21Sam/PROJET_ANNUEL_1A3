<?php 
session_start();
if(!isset($_SESSION['pseudo']) AND !isset($_SESSION['id_user'])){
  header('Location: login_page_forum.php');
}
require('forum_article.php'); 
require('forum_answerpost.php');
require('forum_rep_affichage.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sujet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="style_2.css" rel="stylesheet" />
    <link href="STYLE/style_global.css" rel="stylesheet" />
</head>
<header>
    <?php include "includes/forum_navbar.php"; ?>
  </header>
<body style="padding-top: 6rem;">
    <div class="container mt-5">
        <?php if(isset($errorMsg)): ?>
            <div class="alert alert-danger" role="alert">
                <?= $errorMsg; ?>
            </div>
        <?php endif; ?>

        <?php if(isset($topicDate)): ?>
            <div class="card">
                <div class="card-body">
                    <h3 class="card-title"><?= $topicTitle; ?></h3>
                    <hr>
                    <p class="card-text"><?= $topicContent; ?></p>
                    <hr>
                    <small class="text-muted"><?= $topicAuthor .' '. $topicDate; ?></small>
                </div>
            </div>
            <br>
            <div>
                <form action="" class="form-group" method="POST">
                    <div class="mb-3">
                    <label for="">Réponse</label><br>
                    <textarea maxlength="250" name="rep_content" id=""></textarea>
                    <button class="btn btn-success" type="submit" name="rep_post">Répondre</button>
                </div>
                </form>
                <?php
                while ($respons = $getRespons->fetch()) {
                    // Formatage de la date en PHP
                    $formattedDate = date("d/m/Y", strtotime($respons['rep_date_publi'])); // Par exemple, format "jour/mois/année"
                    ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <!-- Affichage du contenu de la réponse -->
                            <?= $respons['rep_content']; ?> <br>
                            <!-- Affichage de la date de publication et de l'auteur -->
                            <small class="text-muted">Publié le : <?= $formattedDate; ?> par <?= htmlspecialchars($respons['pseudo']); ?></small>
                        </div>
                    </div>
                    <?php
                }
                ?>

            </div>
        <?php endif; ?>
    </div>
</body>
</html>
