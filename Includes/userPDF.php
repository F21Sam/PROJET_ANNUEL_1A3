<?php
use Dompdf\Dompdf;
use Dompdf\Options;

session_start();
require "dompdf/autoload.inc.php";

if (isset($_SESSION['id_user'])) {
    $userId = $_SESSION['id_user'];
    $pseudo = $_SESSION['pseudo'];

    include "dbpa.php";

    $data = $dbh->prepare('SELECT nom, prenom, date_naissance, adresse, code_postal, ville, telephone, date_modif FROM USER WHERE id_user = ?');
    $data->execute([$userId]);
    $user = $data->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        ob_start();
        ?>
        <!doctype html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport"
                  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
            <meta http-equiv="X-UA-Compatible" content="ie=edge">
            <title>Vos informations personnelles</title>
            <style>
                body {
                    font-family: 'rocknroll';
	                src: url('../FONT/RocknRollOne-Regular.ttf') format("truetype");
                    margin: 0;
                    padding: 0;
                    color: #333;
                }

                .container {
                    width: 80%;
                    margin: 20px auto;
                    border-radius: 8px;
                    padding: 20px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                }

                h1, h2, h3 {
                    color: #183425;
                    margin-bottom: 10px;
                }

                p {
                    margin: 10px 0;
                }
            </style>
        </head>
        <body>
        <div class="container">
            <h1>Vos informations personnelles</h1>
            <div class="user-info">
                <h2><?php echo htmlspecialchars($user['nom']) . ' ' . htmlspecialchars($user['prenom']); ?></h2>
                <p>Date de naissance : <?php echo htmlspecialchars($user['date_naissance']); ?></p>
                <p>Adresse : <?php echo htmlspecialchars($user['adresse']); ?></p>
                <p>Code Postal : <?php echo htmlspecialchars($user['code_postal']); ?></p>
                <p>Ville : <?php echo htmlspecialchars($user['ville']); ?></p>
                <p>Téléphone : <?php echo htmlspecialchars($user['telephone']); ?></p>
                <p>Dernière modification : <?php echo htmlspecialchars($user['date_modif']); ?></p>
            </div>
        </div>
        </body>
        </html>
        <?php
        $html = ob_get_clean();

        $options = new Options();
        $options->set('defaultFont', 'Courier');

        $dompdf = new Dompdf($options);

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        ob_end_clean(); // Vide le tampon de sortie pour s'assurer qu'aucun autre contenu n'est envoyé au navigateur car ca peut bloquer le téléchargement parfois
        $dompdf->stream('mes_donnees.pdf', array("Attachment" => true)); // Télécharge le PDF automatiquement
    } else {
        echo "No user data found<br>"; 
    }
} else {
    echo "User not logged in<br>"; 
    header("Location: login.php");
    exit();
}
?>
