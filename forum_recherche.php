<?php
GLOBAL $dbh;
require_once('includes/dbpa.php');

header('Content-Type: application/json');

try {
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $searchTerm = $_GET['search'];
        $requeteAnimaux = $dbh->prepare('SELECT id_topic, id_animal, title, content_topic, date_publi, author FROM TOPIC WHERE title LIKE :searchTerm ORDER BY date_publi DESC');
        $requeteAnimaux->execute([':searchTerm' => '%' . $searchTerm . '%']);
    } else {
        // Si aucun paramètre de recherche n'est fourni, récupérez tous les sujets triés par date de publication
        $requeteAnimaux = $dbh->prepare('SELECT id_topic, id_animal, title, content_topic, date_publi, author FROM TOPIC ORDER BY date_publi DESC');
        $requeteAnimaux->execute();
    }
    
    $results = $requeteAnimaux->fetchAll(PDO::FETCH_ASSOC);

    // Ajouter le nom de l'animal pour chaque sujet
    foreach ($results as &$result) {
        if (!empty($result['id_animal'])) {
            $correspondanceIdAnimal = $dbh->prepare("SELECT name FROM ANIMAL WHERE id_animal = :id_animal");
            $correspondanceIdAnimal->execute([':id_animal' => $result['id_animal']]);
            $animal = $correspondanceIdAnimal->fetch(PDO::FETCH_ASSOC);
            $result['animal_name'] = $animal ? $animal['name'] : 'Aucun animal trouvé';
        } else {
            $result['animal_name'] = 'Aucun animal associé';
        }
    }

    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
