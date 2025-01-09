<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once('includes/journalisations.php');
include_once('includes/header.php');
include_once('includes/navbar.php');
require_once('includes/dbpa.php');
include_once('includes/sidenav.php');

global $dbh;

// Récupération des données des topics
$stmt = $dbh->query("SELECT TOPIC.id_topic,DATE_FORMAT(TOPIC.date_publi, '%d/%m/%Y ') AS date_publi, TOPIC.content_topic,TOPIC.title,
        TOPIC.author,USER.mail AS user_mail,ANIMAL.name AS animal_name FROM TOPIC
    LEFT JOIN USER ON TOPIC.id_user = USER.id_user
    LEFT JOIN ANIMAL ON TOPIC.id_animal = ANIMAL.id_animal order by date_publi DESC
");
$topics = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suppression d'un topic
if (isset($_POST['delete-btn'])) {
    $id = $_POST['id_topic'];

    $query = "DELETE FROM TOPIC WHERE id_topic = :id_topic";
    $statement = $dbh->prepare($query);
    $statement->execute(['id_topic' => $id]);

    header('Location: moderation_forum.php');
    exit;
}
//pour les donees null
function safe_output($value) {
    return htmlspecialchars($value !== null ? $value : 'Aucun');
}
?>
    <div class="container">
        <h1>Modération du Forum</h1>
        <div class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Date de Publication</th>
                        <th>Contenu</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Mail de l'utilisateur</th>
                        <th>Nom de l'animal</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($topics as $topic) : ?>
                        <tr>
                            <form method="post" action="">
                                <input type="hidden" name="id_topic" value="<?php echo $topic['id_topic']; ?>">
                                <td><?php echo safe_output($topic['id_topic']); ?></td>
                                <td><?php echo safe_output($topic['date_publi']); ?></td>
                                <td class="content-column"><?php echo safe_output($topic['content_topic']); ?></td>
                                <td><?php echo safe_output($topic['title']); ?></td>
                                <td><?php echo safe_output($topic['author']); ?></td>
                                <td><?php echo safe_output($topic['user_mail']); ?></td>
                                <td><?php echo safe_output($topic['animal_name']); ?></td>
                                <td>
                                    <button type="submit" class="btn btn-danger btn-sm" name="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce topic ?');">Supprimer</button>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <style>
        /* Table container styling */
        .table-container {
        padding: 20px;
        border-radius: 10px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        background-color: #f9f9f9;
        margin: 20px 0;
        }

        /* Table styling */
        .table {
        width: 100%;
        margin: 0 auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        background-color: #ffffff;
        border-radius: 10px;
        overflow: hidden;
        }

        /* Table header styling */
        .table thead th {
        background-color: #ea1e63;
        color: #ffffff;
        text-align: left;
        padding: 12px;
        }

        /* Table row and cell styling */
        .table tbody tr {
        border-bottom: 1px solid #dddddd;
        }

        .table tbody tr:nth-of-type(even) {
        background-color: #f3f3f3;
        }

        .table tbody td {
        padding: 12px;
        text-align: left;
        }
        .table tbody td.content-column {
            max-width: 600px; 
            white-space: wrap; 
        }
</style>
    <?php include_once('includes/footer.php'); ?>

