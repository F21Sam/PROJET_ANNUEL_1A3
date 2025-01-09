<?php
session_start();

if (!isset($_SESSION['id_user']) || $_SESSION['role_type'] !== 'ADMIN') {
    header('Location: index.php');
    exit();
}
include_once('includes/journalisations.php');
include_once('includes/header.php');
include_once('includes/navbar.php');
require_once ("includes/dbpa.php");
include_once('includes/sidenav.php');
global $dbh;


$stmt = $dbh->query('SELECT mail  FROM USER WHERE newsletter = 1');
$mail = $stmt->fetchAll(PDO::FETCH_ASSOC);
$totalAbonnes = count($mail);

?>
<div class="container">
<h1>Administration de la newsletter</h1>
<a href="accueil.php" class="btn btn-primary float-end  m-5">RETOUR</a>

<h2>Liste des abonnés (<?php echo $totalAbonnes; ?>)</h2>
<ul>
    <?php foreach ($mail as $mail) : ?>
        <li><?php echo $mail['mail']; ?></li>
    <?php endforeach; ?>
</ul>


<h2>Envoyer une newsletter</h2>
<form action="TESTEMAIL/newsletter.php" method="post"class="styled-form ">
    <div>
        <label for="subject">Objet :</label>
        <input type="text" name="subject" id="subject" required>
    </div>
    <div>
        <label for="message">Contenu :</label>
        <textarea name="message" id="message" rows="5" required></textarea>
    </div>
    <button class="btn btn-danger btn-sm" type="submit" name="send">Envoyer</button>
</form>
</div>
<?php include_once("includes/footer.php") ?>
