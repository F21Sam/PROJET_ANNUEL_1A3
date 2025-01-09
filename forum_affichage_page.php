<?php
session_start();
if(!isset($_SESSION['pseudo']) AND !isset($_SESSION['id_user'])){
  header('Location: login_page_forum.php');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Projet_Annuel_Forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="STYLE/forum.css" rel="stylesheet" />
    <link href="STYLE/style_global.css" rel="stylesheet" />
    <link href="STYLE/dark_mode.css" rel="stylesheet" />
</head>
<header>
    <?php include "includes/forum_navbar.php"; ?>
  </header>
<body style="padding-top: 6rem;">
<div class="container mt-5">

    <form id="searchForm">
        <div class="form-group row">
            <div class="input-group">
                <input type="search" name="search" id="searchInput" class="input-text" placeholder="Recherchez un sujet...">
                
                <div class="input-group-append">
                    <button class="btn send-input-btn btn-primary btn-success" type="submit">Envoyer</button>
                </div>
            </div>
        </div>
    </form>
    
    
    <div id="results" class="mt-3"></div>
    <br>
</div>
<script>
    console.log("JavaScript est chargé.");

    const searchInput = document.getElementById('searchInput');
    const resultsDiv = document.getElementById('results');

    function displayResults(data) {
        // Vide les résultats précédents
        resultsDiv.innerHTML = '';

        if (data.error) {
            resultsDiv.innerHTML = `<p>Erreur: ${data.error}</p>`;
            return;
        }

        if (data.length === 0) {
            resultsDiv.innerHTML = '<p>Aucun résultat trouvé.</p>';
            return;
        }

        // Crée une liste des résultats
        const ul = document.createElement('ul');
        ul.className = 'list-group';

        data.forEach(item => {
            const li = document.createElement('li');
            li.className = 'list-group-item';

            // Créer un lien vers l'article du forum
            const link = document.createElement('a');
            link.className = 'orange';
            link.href = `forum_article_page.php?id_topic=${item.id_topic}`;
            link.innerHTML = item.title;

            // Ajouter les autres informations
            const contentSnippet = document.createElement('p');
            contentSnippet.innerHTML = item.content_topic.length > 100 ? item.content_topic.substring(0, 100) + '...' : item.content_topic;

            const datePubli = document.createElement('small');
            datePubli.textContent = `Publié le : ${new Date(item.date_publi).toLocaleDateString('fr-FR')}`;

            const author = document.createElement('small');
            author.textContent = ` | Auteur : ${item.author}`;

            const animal = document.createElement('small');
            author.textContent = ` | Animal : ${item.animal_name}`;

            // Ajouter les éléments au li
            li.appendChild(link);
            li.appendChild(contentSnippet);
            li.appendChild(datePubli);
            li.appendChild(author);

            ul.appendChild(li);
        });

        resultsDiv.appendChild(ul);
    }

    function fetchResults(query = '') {
        fetch(`forum_recherche.php?search=${encodeURIComponent(query)}`)
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

    // Charger les résultats au démarrage de la page
    document.addEventListener('DOMContentLoaded', () => {
        fetchResults(); // Charge tous les sujets au démarrage
    });

    // Recherche en temps réel
    searchInput.addEventListener('input', function(event) {
        const searchQuery = event.target.value.trim(); // trim() pour supprimer les espaces vides au début et à la fin
        fetchResults(searchQuery);
    });
</script>
</body>

    <?php include "includes/dark_mode.php"; ?>
</html>
