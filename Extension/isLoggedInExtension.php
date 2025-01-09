
<?php
header("Access-Control-Allow-Origin: chrome-extension://emchpjcjkfmnadhnhdchaoilndbkapdj");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");
header("Access-Control-Allow-Credentials: true");

session_start();
include_once('../backend/includes/dbpa.php'); // Assurez-vous d'inclure le fichier de connexion à la base de données

$response = array();

if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    $animauxParraine = $dbh->prepare('
        SELECT ANIMAL.race
        FROM ANIMAL
        INNER JOIN SUBSCRIPTION ON ANIMAL.id_animal = SUBSCRIPTION.id_animal
        WHERE SUBSCRIPTION.id_user = :id_user
    ');
    
    $animauxParraine->bindParam(':id_user', $id_user, PDO::PARAM_INT);
    $animauxParraine->execute();
    $animaux = $animauxParraine->fetchAll(PDO::FETCH_ASSOC);

    $response['loggedIn'] = true;
    $response['sponsoredAnimalRaces'] = array_column($animaux, 'race'); // Récupère uniquement les races des animaux parrainés
} else {
    $response['loggedIn'] = false;
}

echo json_encode($response);
?>
