<?php
include '../php/tools/functions.php';
$dbConnexion = dbConnexion();
session_start();
if (isset($_GET['token']) && $_GET['token'] != ''){
    $stmt = $dbConnexion->prepare('SELECT mail FROM utilisateurs WHERE token = ?');
    $stmt->execute([$_GET['token']]);
    $mail = $stmt-> fetchColumn();

}
if (isset($_POST['submit'])) {
    if (!empty($_POST['newPwd']) && !empty($_POST['verifPwd'])) {
        if ($_POST['newPwd'] == $_POST['verifPwd']) {
            $pwd = $_POST['newPwd'];
            var_dump($pwd);
            $hashedPwd = password_hash($pwd, PASSWORD_DEFAULT);
            $stmt = $dbConnexion->prepare('UPDATE utilisateurs SET password = ? WHERE mail = ?');
            $stmt->execute([$hashedPwd , $mail]);
            $stmt = $dbConnexion->prepare('UPDATE utilisateurs SET token = NULL WHERE mail = ?');
            $stmt->execute([$mail]);
            header('Location: connexion.php?success=1');
        }else{
            echo "<div class='alert alert-warning' role='alert'>Les mots de passe ne correspondent pas</div>";
        }
    } else {
        echo "<div class='alert alert-warning' role='alert'>Veuillez completer les champs</div>";
    }
}
if ($mail) {
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <?php include('../includes/headLink.php') ?>
    <link href="../css/styleRenewPwd.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Changement de mot de passe</title>
    <style>
    .custom-icon {
    color: black;
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    right: 10px;
    }
    .custom-submit-btn:hover {background-color: #e8f0fe;}
    .bg-custom {background-color: #0854C7;padding: 40px;border-radius: 15px;}
    .custom-form-input-group {position: relative;}
    .custom-form-input-group input[type=password] {padding-right: 40px;}
    </style>
</head>
<body>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="../css/style.css" rel="stylesheet">
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
            <div class='col-12 col-md-auto text-center pt-1 pr-4 connexion-section'>
                <!-- Icône de connexion et navigation (toujours visible) -->
                <div class="d-none d-md-flex justify-content-center custom-bg-logo rounded-top">
                    <a href="../pages/connexion.php" class="d-block mb-2" style="max-width: 50px;">
                        <img src="../assets/icon_people_.png" class="connexion-logo img-fluid" alt="connexion">
                    </a>
                    <div class="container my-3 connexion-home">
                        <!-- Si l'utilisateur est connecté, afficher le message de bienvenue et les options de profil et de déconnexion -->
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
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
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
                    <!-- Élément de menu admin -->
                                    </ul>
            </div>
        </nav>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
<!--<script src="../js/scriptHeaderNav.js" ></script>-->
        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-custom">
                        <h1 class="text-center text-white mb-4">Récuperation de mot de passe</h1>
                        <form method="post">
                            <label for="newPwd" class="form-label custom-form-label text-white">Nouveau mot de passe :</label>
                            <div class="mb-3 custom-form-input-group">
                            <input type="password" id="newPwd" class="form-control custom-form-input" name="newPwd" placeholder="Nouveau mot de passe">
                            <!-- Assurez-vous que l'ID de cet input est "newPwd" -->
                           </div>

                            <div class="mb-3 custom-form-input-group">
                                <input type="password" class="form-control custom-form-input" name="verifPwd" placeholder="Confirmez le mot de passe">
                                <i class="fas fa-lock custom-icon"></i>
                            </div>
                            <div class="d-grid">
                                <input type="submit" class="btn custom-submit-btn text-white" name="submit" value="valider">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

<?php include('../includes/footer.php') ?>
    <?php include('../includes/scriptLink.php') ?>
</body>
</html>
<?php } else{
    echo "Vous n'êtes pas autorisé à accéder a cette page.";
}