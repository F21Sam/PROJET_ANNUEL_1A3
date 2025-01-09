<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
//includes
    include_once('includes/journalisations.php');
    include_once('includes/header.php');
    include_once('includes/navbar.php');
    include_once('includes/sidenav.php');
    
global $dbh;
require_once("includes/dbpa.php");

// Traitement de la mise à jour
if (isset($_POST['update-btn'])) {
    $id_user = $_POST['id_user'];
    $role_type = $_POST['update-btn'];

    $query = "UPDATE USER SET role_type = :role_type WHERE id_user = :id_user";
    $statement = $dbh->prepare($query);
    $statement->execute([
        'role_type' => $role_type,
        'id_user' => $id_user
    ]);
}

// Bannissement et débannissement


if (isset($_POST['bann-btn'])) {
    $id_user = $_POST['id_user'];
    $banned = $_POST['bann-btn']; // 1 pour bannir, 0 pour débannir

    $query = "UPDATE USER SET banned = :banned WHERE id_user = :id_user";
    $statement = $dbh->prepare($query);
    $statement->execute([
        'banned' => $banned,
        'id_user' => $id_user
    ]);
}

// Suppression ( un try catch pour eviter ler erreurs de cle etrangere)
if (isset($_POST['delete-btn'])) {
    try {
        $id_user = $_POST['id_user'];
        
        $query = "DELETE FROM USER WHERE id_user = :id_user";
        $statement = $dbh->prepare($query);
        $statement->execute(['id_user' => $id_user]);

        echo "<div class='alert alert-success'>Utilisateur supprimé avec succès.</div>";
    } catch (Exception $e) {
        echo "<div class='alert alert-danger'>Cet utilisateur ne peut pas être supprimé car il est relié à d'autres tables de la base de donnés.</div>";
    }
}

//trier et filtrer

$order = isset($_GET['order']) ? $_GET['order'] : 'id_user';
$dir = isset($_GET['dir']) ? $_GET['dir'] : 'ASC';
$query = "SELECT id_user, pseudo, prenom, nom, mail, date_ins, role_type, banned FROM USER";

$query .= " ORDER BY $order $dir";

$stmt = $dbh->prepare($query);
$stmt -> execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<div class="container">
<h1>Gestion des utilisateurs</h1>

<div class="card col-lg-6 col-sm-5 m-3 mx-auto">
    <div class="card-body d-flex justify-content-between align-items-center">
        <h4 class="mb-0">Ajouter un nouvel utilisateur</h4>
        <a href="new_user.php" class="btn btn-primary">Ajouter</a>
    </div>
</div>
<div class="table-container">
<table class="table">
    <tr>
        <th><a href="?order=id_user&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>ID</th>
        <th><a href="?order=pseudo&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Pseudo</th>
        <th><a href="?order=nom&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Nom</th>
        <th><a href="?order=prenom&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Prénom</th>
        <th><a href="?order=mail&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Email</th>
        <th><a href="?order=date_ins&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Date d'inscription</th>
        <th><a href="?order=role_type&dir=<?php echo $dir === 'ASC' ? 'DESC' : 'ASC'; ?>"><i class="fas fa-sort"></i></a>Rôle</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($users as $user): ?>
        <tr>
            <form method="post">
                <input type="hidden" name="id_user" value="<?php echo $user['id_user']; ?>">
                <td><?php echo $user['id_user']; ?></td>
                <td><?php echo $user['pseudo']; ?></td>
                <td><?php echo $user['nom']; ?></td>
                <td><?php echo $user['prenom']; ?></td>
                <td><?php echo $user['mail']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($user['date_ins'])); ?></td>
                <td>
                <?php if ($user['role_type'] == "MEMBRE" ): ?>
                        <button type="submit" class="btn btn-dark btn-sm" name="update-btn" value='ADMIN' onclick="return confirm('Êtes-vous sûr de vouloir nommer cette utilisateur ADMIN ?');">MEMBRE</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-light btn-sm" name="update-btn" value='MEMBRE' onclick="return confirm('Êtes-vous sûr de vouloir retirer le role d\'admin à cet utilisateur ?');">ADMIN</button>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if ($user['banned'] == 1 ): ?>
                        <button type="submit" class="btn btn-success btn-sm" name="bann-btn" value="0" onclick="return confirm('Êtes-vous sûr de vouloir débannir cet utilisateur ?');">Débannir</button>
                    <?php else: ?>
                        <button type="submit" class="btn btn-warning btn-sm" name="bann-btn" value="1" onclick="return confirm('Êtes-vous sûr de vouloir bannir cet utilisateur ?');">Bannir</button>
                    <?php endif; ?>
                    <button type="submit" class="btn btn-danger btn-sm" name="delete-btn" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">Supprimer</button>
                </td>
            </form>
        </tr>
    <?php endforeach; ?>
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
         /*l'icone sort */
    .table th a {
    text-decoration: none; 
    color: #7a7d9f; 
    text-align: center;

    }
    .table th a i {
        font-size: 14px; 
        vertical-align: middle; 

    }
    .table th a:hover {
            color: #000;
            transition: background-color 0.3s ease;
    }

</style>
<?php include_once("includes/footer.php"); ?>
