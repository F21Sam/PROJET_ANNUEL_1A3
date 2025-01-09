<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation d'inscription</title>
    <style>
        body {
            background-color: #183425;
            font-family: Arial, sans-serif;
            color: #333;
            text-align: center;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 90vh;
            flex-direction: column;
            background-color: #183425;
            padding: 10px;
        }
        .header-text {
            margin-bottom: 20px;
        }
        .header-text h1 {
            font-size: 72px;
            margin: 0;
            color: #BC644B;
        }
        h2 {
            font-size: 24px;
            margin: 0;
            color: #f2d8be;
        }
        p {
            margin: 20px 0;
            color: #fff;
        }
        a.btn-home {
            background-color: #BC644B;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 20px;
        }
        a.btn-home:hover {
            background-color: #a22300;
        }
        .left-box, .right-box {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
        .right-box img {
            border-radius: 10px;
            max-width: 100%;
            height: auto;
            margin-top: 30px;
        }
        @media (min-width: 768px) {
            .container {
                flex-direction: row;
                justify-content: space-between;
            }
            .left-box, .right-box {
                width: 50%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-box">
            <h2>Inscription réussie</h2>
            <p>Un email de confirmation vous a été envoyé. Veuillez vérifier votre boîte de réception pour confirmer votre inscription.</p>
            <a href="index.php" class="btn-home">Retour à l'accueil</a>
        </div>
        <div class="right-box">
            <img src="IMAGES/panda-rouge-dessin-anime-visage-heureux.jpeg" alt="Panda roux heureux">
        </div>
    </div>
</body>
</html>
