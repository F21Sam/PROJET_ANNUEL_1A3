
<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once('includes/header.php');
include_once('includes/navbar.php');
require_once ("includes/dbpa.php");
include_once('includes/sidenav.php');

global $dbh;
$stmt = $dbh->query("SELECT
        SUBSCRIPTION.id_subscription,
        DATE_FORMAT(SUBSCRIPTION.start_date, '%d/%m/%Y') AS start_date,
        SUBSCRIPTION.id_animal,
        USER.prenom AS user_prenom,
        USER.nom AS user_nom
    FROM SUBSCRIPTION
    LEFT JOIN USER ON SUBSCRIPTION.id_user = USER.id_user
");
$subscriptions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suppression
if (isset($_POST['delete-btn'])) {
    $id = $_POST['id'];

    $query = "DELETE FROM SUBSCRIPTION WHERE id_subscription = :id";
    $statement = $dbh->prepare($query);
    $statement->execute(['id' => $id]);

}
?>
<div class="container">
    <h1>Historique des parrainages</h1>
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>Numéro</th>
                    <th>Date de début</th>
                    <th>ID de l'animal</th>
                    <th>Prenom de l'utilisateur</th>
                    <th>Nom de l'utilisateur</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($subscriptions as $subscription) : ?>
                    <tr>
                        <form method="post" action="">
                            <input type="hidden" name="id" value="<?php echo $subscription['id_subscription']; ?>">
                            <td><?php echo $subscription['id_subscription']; ?></td>
                            <td><?php echo $subscription['start_date']; ?></td>
                            <td><?php echo $subscription['id_animal']; ?></td>
                            <td><?php echo $subscription['user_prenom']; ?></td>
                            <td><?php echo $subscription['user_nom']; ?></td>
                            <td>
                                <button type="submit" class="btn btn-danger btn-sm" name="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet abonnement ?');">Supprimer</button>
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
        width: 90%;
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


</style>
<?php include_once ("includes/footer.php"); ?>