
<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
//includes
    include_once('includes/header.php');
    include_once('includes/navbar.php');
    require_once ("includes/dbpa.php");
    include_once('includes/sidenav.php');

global $dbh;

//tri

$order = isset($_GET['order']) ? $_GET['order'] : 'id';
$dir = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';
$query= ("SELECT id, subject, DATE_FORMAT(sending_date, '%d/%m/%Y %H:%i:%s') AS sending_date, content FROM NewsletterHistory");

$query .= " ORDER BY $order $dir";

$stmt = $dbh->prepare($query);
$stmt -> execute();

$newsletters = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Suppression
if (isset($_POST['delete-btn'])) {
    $id= $_POST['id'];

    $query = "DELETE FROM NewsletterHistory WHERE id = :id";
    $statement = $dbh->prepare($query);
    $statement->execute(['id' => $id]);
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}



?>
<div class="container">
<h1>Historique des newsletters</h1>
<div class="table-container">
    <table class="table">
        <thead>
            <tr>
                <th><a href="?order=id&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Numéro</th>
                <th>Objet</th>
                <th><a href="?order=sending_date&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Date d'envoi</th>
                <th>Contenu</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        <form method="post" action="">
            <?php foreach ($newsletters as $newsletter) : ?>
                <tr>
                    <input type="hidden" name="id" value="<?php echo $newsletter['id']; ?>">
                    <td><?php echo $newsletter['id']; ?></td>
                    <td><?php echo $newsletter['subject']; ?></td>
                    <td><?php echo $newsletter['sending_date']; ?></td>
                    <td><?php echo $newsletter['content']; ?></td>
                    <td>
                        <button type="submit" class="btn btn-danger btn-sm" name="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce message ?');">Supprimer</button>
                    </td>
                </tr>
                </form>        
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