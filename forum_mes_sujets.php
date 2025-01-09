<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mes Sujets et Réponses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/forum.css" rel="stylesheet" />
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/dark_mode.css" rel="stylesheet" />
</head>
<body style="padding-top: 6rem;">
<header>
    <?php include "includes/forum_navbar.php"; 
    include_once('includes/dbpa.php');

    $id_user = $_SESSION['id_user'];
    
    $sujetsRequete = $dbh->prepare('SELECT id_topic, title, content_topic, date_publi FROM TOPIC WHERE id_user = ?');
    $sujetsRequete->execute([$id_user]);
    $topics = $sujetsRequete->fetchAll(PDO::FETCH_ASSOC);
    
    $repRequete = $dbh->prepare('SELECT rep.id_rep, rep.id_user, rep.pseudo, rep.id_topic, rep.rep_content, rep.rep_date_publi, t.title 
                                 FROM REP_TOPIC rep
                                 JOIN TOPIC t ON rep.id_topic = t.id_topic
                                 WHERE rep.id_user = ?');
    $repRequete->execute([$id_user]);
    $responses = $repRequete->fetchAll(PDO::FETCH_ASSOC);
    ?>
</header>
<div class="container mt-5">
    <h1>Mes Sujets</h1>
    <div id="results" class="mt-3">
        <?php if (count($topics) > 0): ?>
            <ul class="list-group">
                <?php foreach ($topics as $topic): ?>
                    <li class="list-group-item ">
                        <a class="orange" href="forum_article_page.php?id_topic=<?= $topic['id_topic'] ?>"><?= htmlspecialchars($topic['title']) ?></a><br>
                        <!--<p><?= nl2br(htmlspecialchars_decode($topic['content_topic'])) ?></p>-->
                        <small>Publié le : <?= (new DateTime($topic['date_publi']))->format('d/m/Y') ?></small>
                        <button class="btn btn-danger btn-sm float-end delete-button" data-id="<?= $topic['id_topic'] ?>">Supprimer</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez créé aucun sujet.</p>
        <?php endif; ?>
    </div>

    <h1 class="mt-5">Mes Réponses</h1>
    <div id="responses" class="mt-3">
        <?php if (count($responses) > 0): ?>
            <ul class="list-group">
                <?php foreach ($responses as $response): ?>
                    <li class="list-group-item">
                        <a class="orange" href="forum_article_page.php?id_topic=<?= $response['id_topic'] ?>"><?= htmlspecialchars($response['title']) ?></a><br>
                        <p><?= nl2br(htmlspecialchars_decode($response['rep_content'])) ?></p>
                        <small>Publié le : <?= (new DateTime($response['rep_date_publi']))->format('d/m/Y') ?></small>
                        <button class="btn btn-danger btn-sm float-end delete-response-button" data-id="<?= $response['id_rep'] ?>">Supprimer</button>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Vous n'avez répondu à aucun sujet.</p>
        <?php endif; ?>
    </div>
</div>
<script>
    document.querySelectorAll('.delete-button').forEach(button => {
        button.addEventListener('click', function () {
            const topicId = this.dataset.id;
            if (confirm('Voulez-vous vraiment supprimer ce sujet ? Cette action est définitive')) {
                fetch(`includes/suppression_sujet.php?id_topic=${topicId}`, {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Sujet supprimé avec succès.');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression du sujet.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue.');
                });
            }
        });
    });

    document.querySelectorAll('.delete-response-button').forEach(button => {
        button.addEventListener('click', function () {
            const responseId = this.dataset.id;
            if (confirm('Voulez-vous vraiment supprimer cette réponse ? Cette action est définitive')) {
                fetch(`includes/suppression_rep.php?id_rep=${responseId}`, {
                    method: 'GET'
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        alert('Réponse supprimée avec succès.');
                        location.reload();
                    } else {
                        alert('Erreur lors de la suppression de la réponse.');
                    }
                })
                .catch(error => {
                    console.error('Erreur:', error);
                    alert('Une erreur est survenue.');
                });
            }
        });
    });
</script>
<?php include "includes/dark_mode.php"; ?>
</body>
</html>
