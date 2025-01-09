<?php
use Dompdf\Dompdf;
use Dompdf\Options;
use ZipArchive;

session_start();
require "dompdf/autoload.inc.php";

if (isset($_SESSION['id_user'])) {
    $userId = $_SESSION['id_user'];
    $pseudo = $_SESSION['pseudo'];

    include "dbpa.php";

    $data = $dbh->prepare('SELECT nom, prenom, date_naissance, adresse, code_postal, ville, telephone, date_modif, signature FROM USER WHERE id_user = ?');
    $data->execute([$userId]);
    $user = $data->fetch(PDO::FETCH_ASSOC);

    $animauxParraine = $dbh->prepare('
        SELECT ANIMAL.id_animal, ANIMAL.race, ANIMAL.name, ANIMAL.sexe, ANIMAL.date_naiss, ANIMAL.lieu_naiss, ANIMAL.caractere, ANIMAL.espece, ANIMAL.histoire, ANIMAL.signes, ANIMAL.photo
        FROM ANIMAL
        INNER JOIN SUBSCRIPTION ON ANIMAL.id_animal = SUBSCRIPTION.id_animal
        WHERE SUBSCRIPTION.id_user = :id_user
    ');
    $animauxParraine->bindParam(':id_user', $userId, PDO::PARAM_INT);
    $animauxParraine->execute();
    $animauxList = $animauxParraine->fetchAll(PDO::FETCH_ASSOC);

    if ($user && $animauxList) {
        $options = new Options();
        $options->set('defaultFont', 'Courier');
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Pour créer un fichier ZIP
        $zip = new ZipArchive();
        $zipFilename = tempnam(sys_get_temp_dir(), 'certificates') . '.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE) !== TRUE) {
            exit("Impossible d'ouvrir le fichier ZIP");
        }

        foreach ($animauxList as $animal) {
            ob_start();
            $imagePath = realpath(__DIR__ . '/../IMAGES/certificat-parrainage/certificat.png');
            $imageData = base64_encode(file_get_contents($imagePath));
            $src = 'data:image/png;base64,' . $imageData;

            // Chemin de la signature
            $signaturePath = realpath(__DIR__ . '/../' . htmlspecialchars($user['signature']));
            $signatureData = base64_encode(file_get_contents($signaturePath));
            $signatureSrc = 'data:image/png;base64,' . $signatureData;

            // Dimensions de l'image en pixels
            $width = 1444;
            $height = 962;

            // Conversion des dimensions pour Dompdf (1 pixel = 72 / 96 )
            $widthInPoints = $width * (72 / 96);
            $heightInPoints = $height * (72 / 96);

            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1">
                <title>Amimal</title>
                <style>
                    @font-face {
                        font-family: 'rocknroll';
                        src: url('file://<?php echo realpath(__DIR__ . '/../FONT/RocknRollOne-Regular.ttf'); ?>') format("truetype");
                    }

                    body {
                        margin: 0;
                        font-family: 'rocknroll', sans-serif;
                        width: 100%;
                        height: 100%;
                        position: relative;
                    }

                    .background {
                        position: absolute;
                        top: 0;
                        left: 0;
                        width: 100%;
                        height: 100%;
                        z-index: -1;
                    }

                    span {
                        display: inline;
                    }

                    h1 {
                        position: absolute;
                        font-size: 58px;
                        color: #183425;
                        z-index: 5;
                        top: 100px;
                        left: 380px;
                    }

                    .texte {
                        position: absolute;
                        font-size: 23px;
                        color: #584343;
                        z-index: 5;
                        top: 240px;
                        left: 260px;
                        display: inline;
                        text-align: center;
                        width: 1000px;
                    }

                    .texte-signatures-personnel {
                        position: absolute;
                        font-size: 23px;
                        color: #3E3333;
                        z-index: 5;
                        top: 434px;
                        left: 524px;
                        display: flex;
                        text-align: center;
                    }

                    .texte-signature-parrain {
                        position: absolute;
                        font-size: 23px;
                        color: #3E3333;
                        z-index: 5;
                        top: 434px;
                        left: 984px;
                        display: flex;
                        text-align: center;
                    }

                    .signature-image {
                        position: absolute;
                        top: 504px;
                        left: 984px;
                        width: 200px; 
                        height: auto;
                        z-index: 5;
                    }

                    .directeur {
                        position: absolute;
                        font-size: 19px;
                        color: #584343;
                        z-index: 5;
                        top: 566px;
                        left: 569px;
                        display: flex;
                        text-align: center;
                    }

                    .responsable {
                        position: absolute;
                        font-size: 19px;
                        color: #584343;
                        z-index: 5;
                        top: 700px;
                        left: 437px;
                        display: flex;
                        text-align: center;
                    }

                    .veterinaire {
                        position: absolute;
                        font-size: 19px;
                        color: #584343;
                        z-index: 5;
                        top: 700px;
                        left: 749px;
                        display: flex;
                        text-align: center;
                    }

                    .orange {
                        color: #BE6248;
                    }
                </style>
                <link rel="icon" href="IMAGES/logo-icon.png" type="image/png">
            </head>

            <body>
                <img src="<?php echo $src; ?>" alt="Certificat" class="background">
                <h1>Certificat de parrainage</h1>
                <p class="texte">En parrainant <?php echo htmlspecialchars($animal['name']); ?>, <?php echo htmlspecialchars($animal['race']); ?>,<br>
                Vous contribuez directement sa protection. Votre engagement auprès du<br>
                <span class="orange">Refuge Amimal</span> nous permet de lui assurer un avenir meilleur.<br>
                <br>
                Merci pour votre amitié pour les animaux <?php echo htmlspecialchars($user['nom'] ?? ''); ?> <?php echo htmlspecialchars($user['prenom'] ?? ''); ?> !</p>

                <p class="texte-signatures-personnel">Signatures du personnel Amimal</p>
                <p class="texte-signature-parrain">Signature du parrain</p>
                <img src="<?php echo $signatureSrc; ?>" alt="Signature" class="signature-image">

                <p class="directeur">Fanta Mamou Samassa<br>Directrice générale du refuge</p>
                <p class="responsable">Quentin Delneuf<br>Responsable des adoptions</p>
                <p class="veterinaire">Ana Fernandes<br>Vétérinaire du refuge</p>
            </body>

            </html>
            <?php
            $html = ob_get_clean();

            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper(array(0, 0, $widthInPoints, $heightInPoints));
            $dompdf->render();

            // Sauvegarde chaque PDF dans une variable
            $output = $dompdf->output();
            $pdfFilename = 'certificat_' . htmlspecialchars($animal['name']) . '.pdf';

            // Ajoute le PDF au  ZIP
            $zip->addFromString($pdfFilename, $output);
        }

        // Fermer le ZIP
        $zip->close();

        // Envoyer le ZIP à l'utilisateur pour téléchargement
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="certificats.zip"');
        header('Content-Length: ' . filesize($zipFilename));
        readfile($zipFilename);

        // Supprimer le fichier temporaire
        unlink($zipFilename);
    } else {
        echo "No user data found<br>";
    }
} else {
    echo "User not logged in<br>";
    header("Location: login.php");
    exit();
}
?>
