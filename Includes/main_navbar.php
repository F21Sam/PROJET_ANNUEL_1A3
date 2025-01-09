<?php
session_start(); // Démarre la session

// Vérifie si l'utilisateur est connecté
if(isset($_SESSION['id_user']) && !empty($_SESSION['pseudo'])) {
    $loggedIn = true;
    $userPseudo = $_SESSION['pseudo']; // Récupère le pseudo de l'utilisateur connecté
} else {
    $loggedIn = false;
    $userPseudo = ''; // Aucun utilisateur connecté
}
?>
<!-- Barre de navigation via Bootstrap, est responsive -->
<nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">

        <!-- Bouton du menu burger s'affiche à gauche car avant le reste -->
        <!-- Est désactivé grace au data-bs-toggle="offcanvas" -->
        <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-expanded="false" aria-label="Toggle navigation">
            <span>
            <svg src="IMAGES/list.svg" width="25" height="25" fill="white" class="bi bi-list" viewBox="0 0 16 16">
                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
            </svg>
            </span>
        </button>


        <div class="logo-container mx-auto">
            <!-- Mettre le bon fichier php pour rediriger à la page d'acceuil -->
            <a href="index.php">
            <img src="IMAGES/logo-Amimal.png" alt="Logo amimal" class="logo-navbar">
            </a>
        </div>

        <!-- Dans le offcanvas il y a tous les éléments qui vont apparaitre dans le menu burger -->
        <!-- Note : pour choisir de quel côté sort le menu offcanvas il faut mettre -end (droite) -start (gauche) -->
        <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
            <div class="offcanvas-header">
                <img src="IMAGES/logo-Amimal.png" alt="Logo amimal" width="70" height="78">
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>

            <!-- voir pour le aria-current="active" pour l'accessibilité -->
            <div class="offcanvas-body">
                <ul class="navbar-nav justify-content-center flex-grow-1">
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'index.php') echo 'active'; ?>" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'le-refuge.php') echo 'active'; ?>" href="le-refuge.php">Le refuge</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'pourquoi-parrainer.php') echo 'active'; ?>" href="pourquoi-parrainer.php">Pourquoi parrainer ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'nos-animaux.php') echo 'active'; ?>" href="nos-animaux.php">Nos animaux</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'boutique.php') echo 'active'; ?>" href="boutique.php">Boutique</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link mx-lg-2 <?php if(basename($_SERVER['PHP_SELF']) == 'forum.php') echo 'active'; ?>" href="forum_affichage_page.php">Forum</a>
                    </li>
                    <a href="nos-animaux.php" class="btn btn-primary d-none d-lg-block btn-orange">Parrainer un animal <svg src="IMAGES/heart.svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                        <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143q.09.083.176.171a3 3 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15" />
                        </svg>
                    </a>

                </ul>
            </div>

        </div>

        <?php if($loggedIn) ?>
            <span class="text-white"><?= htmlspecialchars($userPseudo); ?></span>
            <div class="btn">
            <?php if ($loggedIn): ?>
                <a href="mon-profil.php">
                    <img src="IMAGES/user_4.png" height="17px" width="17px"/>
                </a>
            <?php else: ?>
                <a href="login_page.php">
                    <img src="IMAGES/user_4.png" height="17px" width="17px"/>
                </a>
            <?php endif; ?>
        </div>
        
        <div class="btn">
            <a id="toggle-dark-mode">
                <img id="toggle-logo" src="IMAGES/moon.png" height="17px" width="17px"/>
            </a>
        </div>

        
    </div>
</nav>

<!-- Sans ce script le menu burger ne fonctionne pas, c'est l'import du ficher Bootstrap (qui contient du JavaScript) --> 
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
