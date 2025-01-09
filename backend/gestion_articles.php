<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once("includes/header.php");
include_once('includes/navbar.php');
include_once('includes/journalisations.php'); 
require('includes/dbpa.php');
include_once('includes/sidenav.php');


$article = null;
$pages = [];
$articles = [];
$id = null;
$mode = 'list'; 


$stmt = $dbh->query("SELECT id, name FROM PAGES");
$pages = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout et de modification
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $page_id = $_POST['page_id'];
    $section = $_POST['section'];
    $title = $_POST['title'];
    $content = $_POST['content'];
    $id = isset($_POST['id']) ? $_POST['id'] : null;

    if ($id) {
        
        $stmt = $dbh->prepare("UPDATE ARTICLES SET page_id = ?, section = ?, title = ?, content = ? WHERE id = ?");
        $stmt->execute([$page_id, $section, $title, $content, $id]);
    } else {
        
        $stmt = $dbh->prepare("INSERT INTO ARTICLES (page_id, section, title, content) VALUES (?, ?, ?, ?)");
        $stmt->execute([$page_id, $section, $title, $content]);
    }

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $stmt = $dbh->prepare("DELETE FROM ARTICLES WHERE id = ?");
    $stmt->execute([$id]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Préparer la modification
if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $stmt = $dbh->prepare("SELECT page_id, section, title, content FROM ARTICLES WHERE id = ?");
    $stmt->execute([$id]);
    $article = $stmt->fetch(PDO::FETCH_ASSOC);
    $mode = 'edit';
}

// Récupérer les articles
$stmt = $dbh->query("SELECT ARTICLES.id, PAGES.name AS page_name, section, title, content FROM ARTICLES JOIN PAGES ON ARTICLES.page_id = PAGES.id");
$articles = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<div class="container">
<div class="container">
        <h1>Gestion des Articles</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Page</th>
                    <th>Section</th>
                    <th>Titre</th>
                    <th>Contenu</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($articles as $article): ?>
                <tr>
                    <td><?php echo htmlspecialchars($article['id']); ?></td>
                    <td><?php echo htmlspecialchars($article['page_name']); ?></td>
                    <td><?php echo htmlspecialchars($article['section']); ?></td>
                    <td><?php echo htmlspecialchars($article['title']); ?></td>
                    <td><?php echo htmlspecialchars($article['content']); ?></td>
                    <td>
                        <a href="?edit=<?php echo $article['id']; ?>" class="btn btn-warning btn-sm">Modifier</a>
                        <a href="?delete=<?php echo $article['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="container">
        <h1><?php echo $mode == 'edit' ? 'Modifier' : 'Ajouter'; ?> un Article</h1>
        <form method="POST" class="styled-form">
            <?php if ($mode == 'edit'): ?>
                <input type="hidden" name="id" value="<?php echo htmlspecialchars($article['id']); ?>">
            <?php endif; ?>
            <div class="mb-3">
                <label for="page_id" class="form-label">Page</label>
                <select class="form-select" id="page_id" name="page_id" required>
                    <?php foreach ($pages as $page): ?>
                    <option value="<?php echo $page['id']; ?>" <?php echo isset($article['page_id']) && $article['page_id'] == $page['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($page['name']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="section" class="form-label">Section</label>
                <input type="text" class="form-control" id="section" name="section" value="<?php echo htmlspecialchars($article['section'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input type="text" class="form-control" id="title" name="title" value="<?php echo htmlspecialchars($article['title'] ?? ''); ?>" required>
            </div>
            <div class="mb-3">
                <label for="content" class="form-label">Contenu</label>
                <textarea class="form-control" id="content" name="content" rows="5" required><?php echo htmlspecialchars($article['content'] ?? ''); ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?php echo $mode == 'edit' ? 'Modifier' : 'Ajouter'; ?></button>
        </form>
        
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
table th:nth-child(5),
table td:nth-child(5) {
    max-width: 600px;
    overflow: hidden;
    text-overflow: ellipsis; 
    white-space: wrap;
}
</style>




    <?php include_once("includes/footer.php") ?>

