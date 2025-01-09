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


$id_animal = $_GET['id'] ?? null;

if (!$id_animal) {
    // Redirection si aucun ID n'est fourni
    header('Location: gestion_animaux.php');
    exit;
}

// Récupérer les informations de l'animal à modifier
$stmt = $dbh->prepare("SELECT id_animal, race, name, sexe, date_naiss, lieu_naiss, caractere, espece, histoire, signes, photo FROM ANIMAL WHERE id_animal = :id_animal");
$stmt->execute([':id_animal' => $id_animal]);
$animal = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$animal) {
    // Redirection si l'animal n'est pas trouvé
    header('Location: gestion_animaux.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $race = $_POST['race'];
    $name = $_POST['name'];
    $sexe = $_POST['sexe'];
    $date_naiss = $_POST['date_naiss'];
    $lieu_naiss = $_POST['lieu_naiss'];
    $caractere = $_POST['caractere'];
    $espece = $_POST['espece'];
    $histoire = $_POST['histoire'];
    $signes = $_POST['signes'];
    $photo = $_POST['photo'];

    // Mettre à jour les informations de l'animal
    $stmt = $dbh->prepare("UPDATE ANIMAL SET race = :race, name = :name, sexe = :sexe, date_naiss = :date_naiss, lieu_naiss = :lieu_naiss, caractere = :caractere, espece = :espece, histoire = :histoire, signes = :signes, photo = :photo WHERE id_animal = :id_animal");
    $stmt->execute([
        ':race' => $race,
        ':name' => $name,
        ':sexe' => $sexe,
        ':date_naiss' => $date_naiss,
        ':lieu_naiss' => $lieu_naiss,
        ':caractere' => $caractere,
        ':espece' => $espece,
        ':histoire' => $histoire,
        ':signes' => $signes,
        ':photo' => $photo,
        ':id_animal' => $id_animal
    ]);

    // Redirection après la mise à jour
    header('Location: gestion_animaux.php');
    exit;
}
?>
<a href="gestion_animaux.php" class="btn btn-primary float-end m-5">RETOUR</a>

<div class="container">
<h2>Modifier l'animal</h2>

<form method="post" action="" class="styled-form">

    <div>
        <label for="id_animal">ID Animal:</label>
        <input type="text" name="id_animal"  value="<?php echo $animal['id_animal']; ?>" readonly>
    </div>
    
    <div>
        <label for="race">Race:</label>
        <input type="text" name="race"value="<?php echo $animal['race']; ?>">
    </div>
    
    <div>
        <label for="name">Name:</label>
        <input type="text" name="name" value="<?php echo $animal['name']; ?>">
    </div>
    
    <div>
        <label for="sexe">Sexe:</label>
        <input type="text" name="sexe"  value="<?php echo $animal['sexe']; ?>">
        <label for="date_naiss">Date de Naissance:</label>
        <input type="date" name="date_naiss" value="<?php echo $animal['date_naiss']; ?>">
    </div>
    
    <div>
        <label for="lieu_naiss">Lieu de Naissance:</label>
        <input type="text" name="lieu_naiss"   value="<?php echo $animal['lieu_naiss']; ?>">
    </div>
    
    <div>
        <label for="caractere">Caractère:</label>
        <input type="text" name="caractere"  value="<?php echo $animal['caractere']; ?>">
    </div>
    
    <div>
        <label for="espece">Espèce:</label>
    
        <textarea name="espece" rows="7" cols="150"><?php echo $animal['espece']; ?></textarea>
    </div>
    
    <div>
        <label for="histoire">Histoire:</label>
        <textarea name="histoire" rows="7" cols="150"><?php echo $animal['histoire']; ?></textarea>
    </div>
    
    <div>
        <label for="signes">Signes Distinctifs:</label>
        <textarea name="signes"  rows="7" cols="150"><?php echo $animal['signes']; ?></textarea>
    </div>
    
    <div>
        <label for="photo">Photo (chemin relatif):</label>
        <input type="text" name="photo" value="<?php echo $animal['photo']; ?>">
    </div>
    
    <div>
        <button type="submit" class="btn btn-warning btn-sm">Modifier</button>
    </div>

</form>
</div>
<?php include_once("includes/footer.php") ?>

