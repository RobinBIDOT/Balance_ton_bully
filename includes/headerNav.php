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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link href="../css/style.css" rel="stylesheet">
    <link href="../css/styleNav.css" rel="stylesheet">
</head>
<body>
<div class='container-fluid p-0 rounded'>
    <header class='bg-light text-black rounded-top'>
        <div class='row align-items-center justify-content-between m-0'>
            <div class='col-12 col-md-auto p-2 text-center'>
                <a href='../pages/index.php'>
                    <img src='../assets/Logo_site.png' class='img-fluid perso_logoSize' alt='logo du site' />
                </a>
            </div>
            <div class='col d-flex justify-content-center'>
                <div class="content">
                    <div class="search-bar">
                        <input type="text" placeholder="Entrez votre recherche" aria-label="search" class="search-bar__input">
                        <button aria-label="submit search" class="search-bar__submit">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class='col-12 col-md-auto text-center pt-1 pr-4 connexion-section '>
                <!-- Icône de connexion et navigation (toujours visible) -->
                <div class="d-none d-md-flex justify-content-center custom-bg-logo rounded-top">
                    <a href="../pages/connexion.php" class="d-block mb-2" style="max-width: 50px;">
                        <img src="../assets/🦆 icon _people_.png" class="connexion-logo img-fluid" alt="connexion">
                    </a>
                    <div class="container my-3">
                        <!-- Si l'utilisateur est connecté, afficher le message de bienvenue et les options de profil et de déconnexion -->
                        <?php if (isset($_SESSION['nickName'])) { ?>
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-2">
                                    <p class="text-primary text-white mb-2">Bienvenue, <?php echo htmlspecialchars($_SESSION['nickName']); ?></p>
                                </div>
                                <?php
                                    // Bouton pour accéder à la page de profil administrateur 
                                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'administrateur') { ?>
                                    <a href="../pages/profilAdmin.php" class="btn btn-warning my-2">Profil admin</a>
                                <?php }
                                    // Bouton pour accéder à la page de profil professionnel de santé
                                    if (isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'sante') { ?>
                                        <a href="../pages/profilSante.php" class="btn btn-success my-2">Profil santé</a>
                                    <?php } ?>
                                <a href="../pages/deconnexion.php" class="deconnexion-btn">Déconnexion</a>
                                <a href="../pages/account.php" class="btn btn-primary my-2">Votre profil</a>
                                    <a href="../php/deconnexion.php" class="btn btn-danger my-2">Déconnexion</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Barre de navigation -->
            <div class="container-fluid p-0 rounded">
                <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-bottom">
                    <!-- Bouton pour le menu burger -->
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                    <!-- Contenu du menu -->
                    <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                        <ul class="navbar-nav">
                            <!-- Réduire la taille du texte des éléments de la barre de navigation pour le responsive -->
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/actualites.php">Actualités</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/accueilForum.php">Forum</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/consultations.php">Rendez-vous</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/qui-somme-nous.php">Qui sommes-nous ?</a>
                            </li>
                            <!-- Élément supplémentaire pour le responsive -->
                            <div class="d-md-none">
                                <div class="mt-2">
                                    <a href="../pages/connexion.php" class="d-block nav-link text-black text-center mx-5">
                                        Connexion
                                    </a>
                                </div>
                            </div>
                            <!-- Élément de menu déroulant -->
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle text-black text-center mx-3 mx-md-5" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Vous êtes une école
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item text-center" href="../pages/formations.php">Formation</a></li>
                                    <li><a class="dropdown-item text-center" href="../pages/intervention.php">Intervention</a></li>
                                </ul>
                            </li>
                            <!-- Élément de menu admin -->
                            <?php if (isset($_SESSION['nickName']) && $_SESSION['id_role'] === 1) { ?>
                                <li class="nav-item">
                                    <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/profilAdmin.php">Page administrateur</a>
                                </li>
                            <?php } ?>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </header>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
<script>


    document.addEventListener('DOMContentLoaded', function() {
        // Sélectionne le bouton du menu burger
        const burgerButton = document.querySelector('.navbar-toggler');

        // Sélectionne le contenu du menu
        const menuContent = document.querySelector('.navbar-collapse');

        // Ajoute un gestionnaire d'événements au clic sur le bouton du menu burger
        burgerButton.addEventListener('click', function() {
            // Bascule la classe 'show' pour afficher ou cacher le menu
            menuContent.classList.toggle('show');
        });
    });


    /**
     * Cette fonction est exécutée lorsque le document HTML est complètement chargé et prêt à être manipulé.
     * Elle affiche le menu déroulant au survol de l'élément avec la classe 'dropdown'.
     * Elle utilise la fonction hover de jQuery pour détecter le survol de la souris et fadeIn/fadeOut pour afficher/cacher le menu déroulant.
     */
    $(document).ready(function() {
        $('.dropdown').hover(function() {
            // Affiche le menu déroulant au survol
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeIn(500);
        }, function() {
            // Masque le menu déroulant lorsque la souris quitte l'élément
            $(this).find('.dropdown-menu').stop(true, true).delay(100).fadeOut(500);
        });
    });

    /**
     * Cette fonction est exécutée lorsque le document HTML est complètement chargé et prêt à être manipulé.
     * Elle bascule l'affichage de la barre de recherche lors du clic sur l'élément avec la classe 'search-bar__submit'.
     * Elle utilise la fonction click de jQuery pour détecter le clic et toggleClass pour ajouter ou supprimer la classe 'show-search'.
     */
    $(document).ready(function() {
        $(".search-bar__submit").click(function() {
            // Bascule l'affichage de la barre de recherche
            $(".search-bar__input").toggleClass("show-search");
        });
    });


    /**
     * Initialise les interactions avec le logo pour un effet 3D et de surbrillance au survol.
     * Utilise la bibliothèque GSAP pour animer le logo.
     *
     * Le logo s'agrandit légèrement et devient plus lumineux lorsqu'il est survolé.
     * Il subit également une rotation en 3D en fonction de la position de la souris.
     */
    $(document).ready(function() {
        /**
         * Gère les événements de survol (mouseenter et mouseleave) sur le logo.
         *
         * @listens {MouseEvent} mouseenter - Lorsque la souris entre sur le logo.
         * @listens {MouseEvent} mouseleave - Lorsque la souris quitte le logo.
         */
        $(".perso_logoSize").on("mouseenter mouseleave", function(e) {
            if (e.type === "mouseenter") {
                // Applique un effet d'agrandissement et de surbrillance au logo
                gsap.to(this, { duration: 0.3, scale: 1.05, filter: "brightness(1.2)" });

                // Ajoute l'écouteur d'événements pour la rotation 3D
                this.addEventListener("mousemove", onMove);
            } else {
                // Retire l'écouteur d'événements et réinitialise les styles du logo
                this.removeEventListener("mousemove", onMove);
                gsap.to(this, { duration: 0.5, rotationY: 0, rotationX: 0, transformPerspective: 1000, scale: 1, filter: "brightness(1)" });
            }
        });

        /**
         * Applique une rotation 3D au logo en fonction de la position de la souris.
         *
         * @param {MouseEvent} e - L'événement de mouvement de la souris.
         */
        function onMove(e) {
            var rect = this.getBoundingClientRect();
            var x = e.clientX - rect.left;
            var y = e.clientY - rect.top;
            var px = x / rect.width;
            var py = y / rect.height;
            var rotateY = 30 * (px - 0.5);
            var rotateX = -30 * (py - 0.5);

            // Applique la rotation 3D au logo
            gsap.to(this, { duration: 0.5, rotationY: rotateY, rotationX: rotateX, transformPerspective: 1000 });
        }
    });
</script>
</body>
</html>