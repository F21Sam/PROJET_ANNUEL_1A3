<?php
session_start();
include_once('includes/dbpa.php');
if (!$loggedIn) {
    header('Location: login_page.php');
    exit();
}
if (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];

    // Requête pour récupérer le rôle de l'utilisateur
    $rechercheRole = $dbh->prepare('SELECT role_type, pseudo FROM USER WHERE id_user = ?');
    $rechercheRole->execute([$id_user]);
    $utilisateur = $rechercheRole->fetch(PDO::FETCH_ASSOC);
    $userRole = $utilisateur['role_type'];
    $userPseudo = $utilisateur['pseudo'];
}
?>
<!-- Menu veritcale sur les pages de compte -->


<nav class="col-md-3 col-lg-2 sidebar">
    <ul class="nav flex-column">

    <?php if($loggedIn): ?>
    <span class="nav-item">Bienvenue ! <?= htmlspecialchars($userPseudo); ?></span>
        
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'mes-parrainages.php') echo 'active'; ?>" href="mes-parrainages.php">Mes parrainages</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'mon-panier.php') echo 'active'; ?>" href="mon-panier.php">Mon panier</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'mes-commandes.php') echo 'active'; ?>" href="mes-commandes.php">Mes commandes</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'mon-profil.php') echo 'active'; ?>" href="mon-profil.php">Mon profil</a>
        </li>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'forum_mes_sujets.php') echo 'active'; ?>" href="forum_mes_sujets.php">Mes messages</a>
        </li>
        <?php if ($userRole == 'ADMIN'): ?>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'backend/') echo 'active'; ?>" href="backend/">Backend</a>
        </li>
        <?php endif; ?>
        <li class="nav-item">
            <a class="nav-link <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="includes/deco.php">Déconnexion</a>
        </li>
        <?php endif; ?>
    </ul>
</nav>