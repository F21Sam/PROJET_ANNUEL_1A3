<?php
session_start();


if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once("includes/header.php");
include_once("includes/navbar.php");
include_once("includes/sidenav.php");
include_once('includes/journalisations.php');
require_once ("includes/dbpa.php");

if (isset($_GET['table'])) {
    $table = $_GET['table'];

    if (preg_match('/^[a-zA-Z0-9_]+$/', $table)) {

        $stmt = $dbh->query("DESCRIBE $table");
        $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $primaryKey = null;
        foreach ($columns as $column) {
            if ($column['Key'] === 'PRI') {
                $primaryKey = $column['Field'];
                break;
            }
        }

        // Récupérer les données de la table
        $stmt = $dbh->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo "Nom de table non valide.";
        exit();
    }
} else {
    echo "Aucune table sélectionnée.";
    exit();
}

// Gérer l'ajout de colonnes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_column'])) {
    $new_column_name = $_POST['new_column_name'];
    $new_column_type = $_POST['new_column_type'];

    if (!empty($new_column_name) && !empty($new_column_type)) {
        if (preg_match('/^[a-zA-Z0-9_]+$/', $new_column_name) && in_array($new_column_type, ['INT', 'VARCHAR(255)', 'BOOLEAN'])) {
            $sql = "ALTER TABLE `$table` ADD `$new_column_name` $new_column_type";
            try {
                $dbh->exec($sql);
                header("Location: gestion_bdd.php?table=$table");
                exit();
            } catch (PDOException $e) {
                echo "Erreur lors de l'ajout de la colonne : " . $e->getMessage();
            }
        } else {
            echo "Nom de colonne ou type non valide.";
        }
    } else {
        echo "Veuillez saisir un nom de colonne et un type.";
    }
}

// Gérer la mise à jour des données de la table
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_table'])) {
    foreach ($rows as $row) {
        $update_data = [];
        foreach ($columns as $column) {
            $field = $column['Field'];
            if (isset($_POST[$field][$row[$primaryKey]])) {
                $update_data[$field] = $_POST[$field][$row[$primaryKey]];
            }
        }

        $update_sets = [];
        foreach ($update_data as $column_name => $value) {
            $update_sets[] = "$column_name = " . $dbh->quote($value);
        }

        if (!empty($update_sets)) {
            $sql = "UPDATE `$table` SET " . implode(", ", $update_sets) . " WHERE $primaryKey = " . $row[$primaryKey];
            try {
                $dbh->exec($sql);
            } catch (PDOException $e) {
                echo "Erreur lors de la mise à jour des données : " . $e->getMessage();
            }
        }
    }
    header("Location: gestion_bdd.php?table=$table");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion de la table <?php echo htmlspecialchars($table); ?></title>
</head>
<body>
    <h2>Gestion de la table <?php echo htmlspecialchars($table); ?></h2>

    <form action="" method="post">
        <input type="hidden" name="update_table" value="1">
        <table class="table">
            <thead>
                <tr>
                    <?php foreach ($columns as $column) : ?>
                        <th><?php echo htmlspecialchars($column['Field']); ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($rows as $row) : ?>
                    <tr>
                        <?php foreach ($columns as $column) : ?>
                            <td>
                                <input type="text" name="<?php echo htmlspecialchars($column['Field']); ?>[<?php echo $row[$primaryKey] ?? 'null'; ?>]" value="<?php echo htmlspecialchars($row[$column['Field']]); ?>">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <button class="btn btn-danger" type="submit">Mettre à jour les données</button>
    </form>





    <?php include_once("includes/footer.php"); ?>
