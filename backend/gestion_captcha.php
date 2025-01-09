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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['question']) && isset($_POST['answer'])) {
    $question = $_POST['question'];
    $answer = $_POST['answer'];
    $image_path = '';

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $uploadDir = 'assets/images/';
        $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

        if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
            $image_path = $uploadFile;
            echo "";
        } else {
            echo "La photo ne s'est pas téléchargée.<br>";
        }
    } else {
        echo "Erreur de téléchargement de l'image.<br>";
    }

    if ($image_path) {
        $stmt = $dbh->prepare("INSERT INTO CAPTCHA (question, answer, image_path) VALUES (?, ?, ?)");
        $stmt->execute([$question, $answer, $image_path]);
        echo "";
    }
}

if (isset($_GET['delete'])) {
    $id_captcha = $_GET['delete'];
    $stmt = $dbh->prepare("DELETE FROM CAPTCHA WHERE id_captcha = ?");
    $stmt->execute([$id_captcha]);
    echo "";
}

$query = "SELECT id_captcha, question, answer, image_path FROM CAPTCHA";
$statement = $dbh->query($query);
$captchas = $statement->fetchAll(PDO::FETCH_ASSOC);
?>
    <style>
    .small-image {
    width: 100px; 
    height: auto; 
}
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
    
<div class = "container">
<h1>Gestion des CAPTCHAS</h1>
    
    <form action="" method="post" enctype="multipart/form-data" class="styled-form">
        <div>
            <label for="question" class="form-label">Question :</label>
            <input type="text" id="question" name="question" required size="120">
        </div>
        <div>
            <label for="answer" class="form-label">Réponse :</label>
            <input type="text" id="answer" name="answer" required size="120">
        </div>
        <div>
            <label for="image_path" class="form-label">Image :</label>
            <input type="file" name="photo" accept="image/*" size="120">
        </div>
        <button class="btn btn-warning btn-sm" type="submit">Ajouter</button>
    </form>

    <h2>CAPTCHAS existants</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Question</th>
            <th>Réponse</th>
            <th>Image</th>
            <th>Action</th>
        </tr>
        <?php foreach ($captchas as $captcha): ?>
            <tr>
                <td><?php echo htmlspecialchars($captcha['id_captcha']); ?></td>
                <td><?php echo htmlspecialchars($captcha['question']); ?></td>
                <td><?php echo htmlspecialchars($captcha['answer']); ?></td>
                <td>
                    <?php if (!empty($captcha['image_path'])): ?>
                        <img src="<?php echo htmlspecialchars($captcha['image_path']); ?>" alt="<?php echo htmlspecialchars($captcha['question']); ?>" class="small-image">
                    <?php else: ?>
                        Aucune image
                    <?php endif; ?>
                </td>
                <td>
                    <a href="?delete=<?php echo $captcha['id_captcha']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce CAPTCHA ?');">Supprimer</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
</div>


    <?php include_once('includes/footer.php'); ?>

