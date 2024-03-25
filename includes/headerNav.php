<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-compatible" content="ie-edge">
    <title>Balance Ton Bully - Header</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/styleNav.css" rel="stylesheet">
</head>
<body>
<div class='container-fluid p-0 rounded'>
    <header class='bg-light text-black rounded-top'>
        <div class='row align-items-center justify-content-between m-0'>
            <div class='col-12 col-md-auto p-2 text-center'>
                <a href='../php/index.php'>
                    <img src='../assets/Logo_site.png' class='img-fluid perso_logoSize' alt='logo du site' />
                </a>
            </div>
            <div class='col d-flex justify-content-center'>
                <form class="form-inline d-flex">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
                    <a href="#!" class="d-flex align-items-center">
                        <i class="bi bi-search" style="font-size: 1.5rem;"></i>
                    </a>
                </form>
            </div>
            <div class='col-12 col-md-auto text-center pt-1 pr-4 connexion-section '>
                <!-- Icône de connexion et navigation (toujours visible) -->
                <div class="d-none d-md-flex justify-content-center custom-bg-logo rounded-top">
                    <a href="../php/connexion.php" class="d-block mb-2" style="max-width: 50px;">
                        <img src="../assets/🦆 icon _people_.png" class="connexion-logo img-fluid" alt="connexion">
                    </a>
                    <div class="container my-3">
                        <!-- Si l'utilisateur est connecté, afficher le message de bienvenue et les options de profil et de déconnexion -->
                        <?php if (isset($_SESSION['nickName'])) { ?>
                            <div class="d-flex flex-column align-items-center justify-content-center" >
                                <div class="mb-2">
                                    <p class="text-primary mb-2">Bienvenue, <?php echo htmlspecialchars($_SESSION['nickName']); ?></p>
                                </div>
                                <a href="../php/account.php" class="btn btn-primary my-2">Votre profil</a>
                                <?php
                                    // Bouton pour accéder à la page de profil administrateur 
                                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrateur') { ?>
                                    <a href="../pages/profilAdmin.php" class="btn btn-warning my-2">Profil admin</a>
                                <?php }
                                    // Bouton pour accéder à la page de profil professionnel de santé
                                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'sante') { ?>
                                        <a href="../pages/profilSante.php" class="btn btn-success my-2">Profil santé</a>
                                    <?php } ?>
                                    <a href="../pages/deconnexion.php" class="btn btn-danger my-2">Déconnexion</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <!-- Menu déroulant (toujours visible) -->
                <div class="d-none d-md-block dropdown custom-bg-dropdown rounded-bottom ">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Vous êtes une école
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="../pages/formations.php">Formation</a></li>
                        <li><a class="dropdown-item" href="../pages/intervention.php">Intervention</a></li>
                    </ul>
                </div>
            </div>
            <div class="container-fluid p-0 rounded">
                <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-bottom">
                    <button class="navbar-toggler m-2" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-5" href="../pages/actualites.php">Actualités</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-5" href="../pages/accueilForum.php">Forum</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-5" href="../pages/consultations.php">Rendez-vous</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-5" href="../pages/qui-somme-nous.php">Qui sommes-nous ?</a>
                            </li>
                            <div class="d-md-none">
                                <div class="mt-2">
                                    <a href="../php/connexion.php" class="d-block nav-link text-black text-center mx-5">
                                        Connexion
                                    </a>
                                </div>
                                <div class="dropdown mt-2">
                                    <a class="nav-link dropdown-toggle nav-link text-black text-center mx-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        Vous êtes une école
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                        <li><a class="dropdown-item text-center" href="../pages/formations.php">Formation</a></li>
                                        <li><a class="dropdown-item text-center" href="../pages/intervention.php">Intervention</a></li>
                                    </ul>
                                </div>
                            </div>
                            <?php
                            if(isset($_SESSION['nickName'])){
                                if($user['id_role'] = 1){ ?>
                                    <li class="nav-item">
                                        <a class="nav-link text-black text-center mx-5" href="../pages/profilAdmin.php">Page administrateur</a>
                                    </li>
                                    <?php
                                }
                            } ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>