<?php
session_start();

// Vérifiez si l'utilisateur est connecté et a le rôle ADMIN
if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}

include_once('includes/header.php');
include_once('includes/navbar.php');
include_once('includes/sidenav.php');

global $dbh;
require_once("includes/dbpa.php");

// Pour les pages 
$logsPerPage = 20;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $logsPerPage;

$totalLogsQuery = $dbh->query('SELECT COUNT(id_logs) AS total FROM LOGS');
$totalLogs = $totalLogsQuery->fetch()['total'];
$totalPages = ceil($totalLogs / $logsPerPage);

$query = $dbh->prepare("SELECT id_logs, ip_address, page_url, user_agent, id_user, DATE_FORMAT(timestamp, '%d/%m/%Y %H:%i:%s') AS timestamp_fr FROM LOGS ORDER BY timestamp DESC LIMIT :offset, :logsPerPage");
$query->bindValue(':offset', $offset, PDO::PARAM_INT);
$query->bindValue(':logsPerPage', $logsPerPage, PDO::PARAM_INT);
$query->execute();
$logs = $query->fetchAll();

if (isset($_GET['delete'])) {
    $id_logs = $_GET['delete'];
    $stmt = $dbh->prepare("DELETE FROM LOGS WHERE id_logs = :id_logs");
    $stmt->execute([':id_logs' => $id_logs]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
?>
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

    .table tbody td.agent-column {
        max-width: 600px; 
        white-space: wrap; 
    }
    .table tbody td.url-column {
        max-width: 600px; 
        white-space: wrap; 
    }
</style>
<a href="accueil.php" class="btn btn-primary float-end">RETOUR</a>
<div class="container">
    <h2>Journalisation des logs</h2>
    
    <div class="table-container">
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Adresse IP</th>
                    <th>URL de la page</th>
                    <th>Agent utilisateur</th>
                    <th>Date et heure</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logs as $log): ?>
                <tr>
                    <td><?php echo htmlspecialchars($log['id_logs']); ?></td>
                    <td><?php echo htmlspecialchars($log['ip_address']); ?></td>
                    <td class="url-column"><?php echo htmlspecialchars($log['page_url']); ?></td>
                    <td class="agent-column"><?php echo htmlspecialchars($log['user_agent']); ?></td>
                    <td><?php echo htmlspecialchars($log['timestamp_fr']); ?></td>
                    <td>
                        <a href="?delete=<?php echo htmlspecialchars($log['id_logs']); ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce log ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item <?php if ($page <= 1) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>"><img src="assets/images/fleche_droite.png" alt="fleche precedent" height="40" ></a>
            </li>
            <?php
            $maxLinks = 5;
            $start = max(1, $page - intval($maxLinks / 2));
            $end = min($totalPages, $page + intval($maxLinks / 2));

            if ($start > 1) {
                echo '<li class="page-item"><a class="page-link" href="?page=1">1</a></li>';
                if ($start > 2) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                echo '<li class="page-item ' . ($i == $page ? 'active' : '') . '">';
                echo '<a class="page-link" href="?page=' . $i . '">' . $i . '</a>';
                echo '</li>';
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    echo '<li class="page-item disabled"><span class="page-link">...</span></li>';
                }
                echo '<li class="page-item"><a class="page-link" href="?page=' . $totalPages . '">' . $totalPages . '</a></li>';
            }
            ?>
            <li class="page-item <?php if ($page >= $totalPages) echo 'disabled'; ?>">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>"><img src="assets/images/fleche_gauche.png" alt="fleche suivant" height="40"></a>
            </li>
        </ul>
    </nav>
</div>
<?php include_once("includes/footer.php"); ?>
