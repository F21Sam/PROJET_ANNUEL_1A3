<?php
require_once("dbpa.php");

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["table"])) {
    $nom_table = $_GET["table"];

    // Validation du nom de la table
    if (!empty($nom_table)) {

        $sql = "DROP TABLE `$nom_table`";

        try {
            $dbh->exec($sql);
            header("Location: ../accueil.php?success=table_dropped");
            exit();
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de la table : " . $e->getMessage();
        }
    } else {
        echo "Nom de la table non spécifié.";
    }
} else {
    echo "Requête non valide.";
}
?>

