<div class='container-fluid p-0 rounded'>
    <header class='bg-light text-black rounded-top'>
        <div class='row align-items-center justify-content-between m-0'>
            <div class='col-12 col-md-auto p-2 text-center'>
                <a href='../pages/index.php'>
                    <img src='../assets/Logo_site.png' class='img-fluid perso_logoSize' alt='logo du site'>
                </a>
            </div>
            <div class="content">
<!--                <div class="search-bar">-->
<!--                    <form action="../pages/accueilForum.php" method="GET">-->
<!--                        <input type="text" placeholder="Entrez votre recherche" aria-label="search" class="search-bar__input" name="searchTitle">-->
<!--                        <button aria-label="submit search" class="search-bar__submit" type="submit">-->
<!--                            <i class="fa-solid fa-magnifying-glass"></i>-->
<!--                        </button>-->
<!--                    </form>-->
<!--                </div>-->
            </div>
            <div class='col-12 col-md-auto text-center pt-1 pr-4 connexion-section'>
                <!-- Icône de connexion et navigation (toujours visible) -->
                <div class="d-none d-md-flex justify-content-center custom-bg-logo rounded-top">
                    <a href="../pages/connexion.php" class="d-block mb-2" style="max-width: 50px;">
                        <img src="../assets/icon_people_.png" class="connexion-logo img-fluid" alt="connexion">
                    </a>
                    <div class="container my-3 connexion-home">
                        <!-- Si l'utilisateur est connecté, afficher le message de bienvenue et les options de profil et de déconnexion -->
                        <?php if (isset($_SESSION['nickName'])) { ?>
                            <div class="d-flex flex-column align-items-center justify-content-center">
                                <div class="mb-2">
                                    <p class="text-primary text-black mb-2">Bienvenue, <?php echo htmlspecialchars($_SESSION['nickName']); ?></p>
                                </div>
                                <a href="../pages/account.php" class="profil-btn">Votre profil</a>
                                <a href="../php/deconnexion.php" class="deconnexion-btn">Déconnexion</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </header>
</div>
<div class="container-fluid p-0 rounded">
    <!-- Barre de navigation -->
    <div class="container-fluid p-0 rounded">
        <nav class="navbar navbar-expand-lg navbar-light bg-light rounded-bottom">
            <!-- Bouton pour le menu burger -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- Contenu du menu -->
            <div class="collapse navbar-collapse justify-content-center" id="navbarSupportedContent">
                <ul class="navbar-nav">
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
                    <?php if (!isset($_SESSION['nickName'])){ ?>
                    <li class="nav-item d-md-none">
                        <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/connexion.php" class="d-block nav-link text-black text-center mx-5">Connexion</a>
                    </li>
                    <?php } ?>
                    <?php if (isset($_SESSION['nickName'])) { ?>
                        <li class="nav-item d-md-none">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../pages/account.php" class="d-block nav-link text-black text-center mx-5">Votre profil</a>
                        </li>
                        <li class="nav-item d-md-none">
                                <a class="nav-link text-black text-center mx-3 mx-md-5" href="../php/deconnexion.php" class="d-block nav-link text-black text-center mx-5">Déconnexion</a>
                        </li>
                    <?php } ?>
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