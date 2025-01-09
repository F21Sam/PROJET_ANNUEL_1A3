<?php
require_once('dbpa.php');

header('Content-Type: application/json');

try {
    $conditions = [];
    $params = [];

    if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
        $conditions[] = 'name LIKE :rechercheTerm';
        $params[':rechercheTerm'] = '%' . $_GET['recherche'] . '%';
    }

    if (isset($_GET['race']) && $_GET['race'] != 'all') {
        $conditions[] = 'race = :race';
        $params[':race'] = $_GET['race'];
    }

    $conditions[] = 'id_animal != 0';
    $whereClause = implode(' AND ', $conditions);

    $sql = 'SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL';
    if (!empty($whereClause)) {
        $sql .= ' WHERE ' . $whereClause;
    }
    $sql .= ' ORDER BY name ASC';

    $requeteAnimaux = $dbh->prepare($sql);
    $requeteAnimaux->execute($params);

    $results = $requeteAnimaux->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
