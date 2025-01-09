<?php 
if(isset($_SESSION['id_user']) && !empty($_SESSION['pseudo'])) {
    $loggedIn = true;
    $userPseudo = $_SESSION['pseudo']; // Récupère le pseudo de l'utilisateur connecté
} else {
    $loggedIn = false;
    $userPseudo = ''; 
}
?>



<nav class="navbar navbar-main navbar-expand px-0 mx-4 shadow-none border-radius-xl navbar-fixed" id="navbarBlur" data-scroll="true">
    <div class="container-fluid py-1 px-3">
        <h4>Bienvenue sur le back-office d'Amimal.org <?php if($loggedIn) ?>
        <span ><?= htmlspecialchars($userPseudo); ?></span>!</h4>
        <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <div><a href="../mon-profil.php" class="btn btn-primary">Mon profil</a></div>
        </div>
        <div class="ms-md-auto pe-md-3 d-flex align-items-center">
        <form class="form-inline my-2 my-lg-0" method="get" action="">
                <div class="input-group input-group-outline">
                <input type="text" class="form-control " style="height:3em;" id="search-input"  name="recherche" placeholder="Recherche...">
                    <button type="submit" class="btn btn-primary" name="search" id="search-button"><img src="assets/images/loupe.png" alt="loupe" height="20"></button>
                </div>
        </form>        
            </div>
        </div>
    </div>
</nav>

