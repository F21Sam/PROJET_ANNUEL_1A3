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

// Récupération des données de la table ANIMAL
$stmt = $dbh->query("SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL");
$animal = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Traitement du formulaire d'ajout
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $id_animal = $_POST['id_animal'] ?? null;
    $race = $_POST['race'];
    $name = $_POST['name'];
    $sexe = $_POST['sexe'];
    $date_naiss = $_POST['date_naiss'];
    $lieu_naiss = $_POST['lieu_naiss'];
    $caractere = $_POST['caractere'];
    $espece = $_POST['espece'];
    $histoire = $_POST['histoire'];
    $signes = $_POST['signes'];
    $photo = '';
    //telechargement de la photo
if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
    $uploadDir = '../IMAGES/animaux_parrainage/';
    $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
        $photo = $uploadFile;
        echo "";
    } else {
        echo "La photo ne s'est pas téléchargée.<br>";
    }
} else {
    echo "Erreur de téléchargement de l'image.<br>";
}
if ($photo) {
    $stmt = $dbh->prepare("INSERT INTO ANIMAL (id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo) 
    VALUES (:id_animal, :race, :name, :sexe, :date_naiss, :lieu_naiss, :caractere, :espece, :histoire, :signes, :photo)");
    $stmt->execute([
        ':id_animal' => $id_animal,
        ':race' => $race,
        ':name' => $name,
        ':sexe' => $sexe,
        ':date_naiss' => $date_naiss,
        ':lieu_naiss' => $lieu_naiss,
        ':caractere' => $caractere,
        ':espece' => $espece,
        ':histoire' => $histoire,
        ':signes' => $signes,
        ':photo' => $photo
    ]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}
}
// Traitement de la suppression
if (isset($_GET['delete'])) {
    $id_animal = $_GET['delete'];
    $stmt = $dbh->prepare("DELETE FROM ANIMAL WHERE id_animal = :id_animal");
    $stmt->execute([':id_animal' => $id_animal]);

    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

// Préparer la modification
if (isset($_GET['edit'])) {
    $id_animal = $_GET['edit'];
    $stmt = $dbh->prepare("SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE id_animal = ?");
    $stmt->execute([$id_animal]);
    $animal_edit = $stmt->fetch(PDO::FETCH_ASSOC);
    $mode = 'edit';
} else {
    $animal_edit = null;
    $mode = 'add';
}


// Vérifie si un animal est sélectionné pour afficher ses détails
if (isset($_GET['id_animal'])) {
    $id_animal = $_GET['id_animal'];
    $stmt = $dbh->prepare("SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE id_animal = :id_animal");
    $stmt->execute([':id_animal' => $id_animal]);
    $animal_detail = $stmt->fetch(PDO::FETCH_ASSOC);
} ?>


<div class="container">
<h2>Gestion des animaux</h2>

<!-- Liste déroulante pour sélectionner un animal -->
<form method="get">
    <select name="id_animal" style="height: 30px">
        <option value="">Sélectionner un animal</option>
        <?php foreach ($animal as $row): ?>
            <option value="<?php echo $row['id_animal']; ?>"><?php echo $row['name']; ?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit" class="btn btn-primary btn-sm">Afficher</button>
</form>

<?php if (isset($animal_detail)) { ?>
    <h3>Détails de l'animal sélectionné</h3>
    <ul>
        <li>ID Animal: <?php echo $animal_detail['id_animal']; ?></li>
        <li>Race: <?php echo $animal_detail['race']; ?></li>
        <li>Nom: <?php echo $animal_detail['name']; ?></li>
        <li>Sexe: <?php echo $animal_detail['sexe']; ?></li>
        <li>Date de Naissance: <?php echo $animal_detail['date_naiss']; ?></li>
        <li>Lieu de Naissance: <?php echo $animal_detail['lieu_naiss']; ?></li>
        <li>Caractère: <?php echo $animal_detail['caractere']; ?></li>
        <li>Espèce: <?php echo $animal_detail['espece']; ?></li>
        <li>Histoire: <?php echo $animal_detail['histoire']; ?></li>
        <li>Signes Distinctifs: <?php echo $animal_detail['signes']; ?></li>
        <li>Photo: <?php echo $animal_detail['photo']; ?></li>
    </ul>
    <!-- Boutons pour modifier et supprimer l'animal -->
    <a href="modifier_animal.php?id=<?php echo $animal_detail['id_animal']; ?>" class="btn btn-warning btn-sm">Modifier</a>
    <a href="gestion_animaux.php?delete=<?php echo $animal_detail['id_animal']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet animal?');">Supprimer</a>
<?php } else {
    echo "<p>Aucun animal sélectionné.</p>";
} ?>


<h3>Ajoutez un nouvel animal</h3>

<form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data" class="styled-form">
    <input type="hidden" name="id_animal"  value="<?php echo $animal_edit['id_animal'] ?? ''; ?>">
    <div>
        <label for="id_animal">ID Animal:</label>
        <input type="text" name="id_animal" value="<?php echo $animal_edit['id_animal'] ?? ''; ?>">
    </div>
    <div>
        <label for="race">Race:</label>
        <input type="text" name="race"  value="<?php echo $animal_edit['race'] ?? ''; ?>">
    </div>
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name"  value="<?php echo $animal_edit['name'] ?? ''; ?>">
    </div>
    <div>
        <label for="sexe">Sexe:</label>
        <select name="sexe" id="sexe">
            <option value="Femelle"<?php echo ' selected'; ?>>Femelle</option>
            <option value="Mâle"<?php echo ' selected'; ?>>Mâle</option>
        </select>

        <label for="date_naiss">Date de Naissance:</label>
        <input type="date" name="date_naiss" value="<?php echo $animal_edit['date_naiss'] ?? ''; ?>">
    </div>
    <div>
        <label for="lieu_naiss">Lieu de Naissance:</label>
        <input type="text" name="lieu_naiss"  value="<?php echo $animal_edit['lieu_naiss'] ?? ''; ?>">
    </div>
    <div>
        <label for="caractere">Caractère:</label>
        <input type="text" name="caractere"  value="<?php echo $animal_edit['caractere'] ?? ''; ?>">
    </div>
    <div>
        <label for="espece">Espèce:</label>
        <textarea name="espece" ><?php echo $animal_edit['espece'] ?? ''; ?></textarea>
    </div>
    <div>
        <label for="histoire">Histoire:</label>
        <textarea name="histoire"><?php echo $animal_edit['histoire'] ?? ''; ?></textarea>
    </div>
    <div>
        <label for="signes">Signes Distinctifs:</label>
        <textarea name="signes"><?php echo $animal_edit['signes'] ?? ''; ?></textarea>
    </div>
    <div>
        <label for="photo">Photo (chemin relatif):</label>
        <input type="file" name="photo" accept="image/*"  value="<?php echo $animal_edit['photo'] ?? ''; ?>">
    </div>
    <div>
        <button class="btn btn-danger btn-sm" type="submit" name="add">Ajouter</button>
    </div>
</form>

</div>

<?php include_once('includes/footer.php');
