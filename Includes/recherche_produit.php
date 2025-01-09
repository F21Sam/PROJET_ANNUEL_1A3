<?php
require_once('dbpa.php');

header('Content-Type: application/json');

try {
    $conditions = [];
    $params = [];

    if (isset($_GET['recherche']) && !empty($_GET['recherche'])) {
        $conditions[] = 'nom_product LIKE :rechercheTerm';
        $params[':rechercheTerm'] = '%' . $_GET['recherche'] . '%';
    }

    if (isset($_GET['category']) && $_GET['category'] != 'all') {
        $conditions[] = 'category = :category';
        $params[':category'] = $_GET['category'];
    }

    $whereClause = implode(' AND ', $conditions);

    $sql = 'SELECT id_product, nom_product, price_product, stock, photo, description, delivery_time, promotion, rating, comments FROM PRODUCT';
    if (!empty($whereClause)) {
        $sql .= ' WHERE ' . $whereClause;
    }
    $sql .= ' ORDER BY nom_product ASC';

    $requeteProduits = $dbh->prepare($sql);
    $requeteProduits->execute($params);

    $results = $requeteProduits->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($results);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
?>
