<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once("includes/header.php");
include_once('includes/navbar.php');
include_once('includes/journalisations.php');
include_once('includes/sidenav.php');



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['photo'])) {
    
    $uploadDir = 'assets/images/';
    $uploadFile = $uploadDir . basename($_FILES['photo']['name']);

    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadFile)) {
        echo "La photo a été téléchargée avec succès.";
    } else {
        echo "La photo ne s'est pas téléchargée.";
    }
}


if (isset($_GET['rename'])) {
    $oldFileName = 'assets/images/' . $_GET['rename'];
    $newFileName = 'assets/images/' . $_POST['newName'];
    if (file_exists($oldFileName)) {
        if (rename($oldFileName, $newFileName)) {
            echo "L'image a été renommée avec succès.";
        } else {
            echo "Impossible de renommer l'image.";
        }
    } else {
        echo "";
    }
}


if (isset($_GET['delete'])) {
    $fileName = 'assets/images/' . $_GET['delete'];
    if (file_exists($fileName)) {
        if (unlink($fileName)) {
            echo "L'image a été supprimée avec succès.";
        } else {
            echo "Impossible de supprimer l'image.";
        }
    } else {
        echo "";
    }
}

?>
<div class="container">
    <h1>Gestion des Photos</h1>


    <form action="" method="post" enctype="multipart/form-data">
        <input type="file" name="photo" accept="image/*">
        <button type="submit" class="btn btn-primary btn-block">Télécharger</button>
    </form>


    <h2>Photos existantes :</h2>
    <div class="row">

        <?php
    
        $photos = glob('assets/images/*');
        foreach ($photos as $photo) {
            echo '<div class="col-md-4 mb-3">';
            echo '<div class="card h-100 ">';
            echo '<img src="' . $photo . '" class="card-img-top" alt="Photo">';
            echo '<div class="card-body  d-flex flex-column">';
            
            echo '<a href="?delete=' . basename($photo) . '" class="btn btn-danger">Supprimer</a>';
            echo '<ul>';
            echo '<li>Nom du fichier: ' . basename($photo) . '</li>';
            echo '<li>Taille: ' . filesize($photo) . ' octets</li>';
            echo '<li>Date de téléchargement: ' . date("Y-m-d H:i:s", filemtime($photo)) . '</li>';
            echo '</ul>';
            
            echo '<form action="?rename=' . basename($photo) . '" method="post">';
            echo '<div class="input-group">';
            echo '<input type="text" name="newName" class="form-control" placeholder="Nouveau nom">';
            echo '<button type="submit" class="btn btn-primary">Renommer</button>';
            echo '</div>';
            echo '</form>';
            echo '</div>';
            echo '</div>'; 
            echo '</div>'; 
            
        }
        
        ?>
        
    </div>
</div>

<?php include_once ("includes/footer.php");?>