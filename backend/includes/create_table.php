
<?php
global $dbh;
require_once("dbpa.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nom_table = $_POST["nom_table"];

    if (!empty($nom_table)) {
        
        
            $sql = "CREATE TABLE IF NOT EXISTS `$nom_table` (
                        id INT AUTO_INCREMENT PRIMARY KEY
                    )";

            try {
                $dbh->exec($sql);
                header("Location: ../accueil.php?success=table_added");
                exit();
            } catch (PDOException $e) {
                echo "Erreur lors de l'ajout de la table : " . $e->getMessage();
            }
        } else {
            echo "Le nom de la table contient des caractères non autorisés.";
        }
    } else {
        echo "Veuillez saisir un nom de table.";
    }

?>
