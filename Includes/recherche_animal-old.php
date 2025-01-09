<?php
require_once('dbpa.php');

header('Content-Type: application/json');

try {
    if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
        $rechercheTerm = $_GET['recherche'];
        $requeteAnimaux = $dbh->prepare('SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE name LIKE :rechercheTerm AND id_animal != 0 ORDER BY name ASC');
        $requeteAnimaux->execute([':rechercheTerm' => '%' . $rechercheTerm . '%']);
    } else {
        // Si aucun paramètre de recherche n'est fourni, récupérez tous les animaux triés par nom
        $requeteAnimaux = $dbh->prepare('SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE id_animal != 0 ORDER BY name ASC');
        $requeteAnimaux->execute();
    }
    
    $results = $requeteAnimaux->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
