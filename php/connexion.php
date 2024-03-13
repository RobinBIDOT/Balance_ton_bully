<?php
// Inclusion du fichier de fonctions et démarrage de la session
include 'c:/xampp/htdocs/Balance_ton_bully/php/tools/functions.php';
session_start();

// Connexion à la base de données
$pdo = dbConnexion();

// Vérification de la soumission du formulaire de connexion
if (isset($_POST['submit'])) {
    if (!empty($_POST['pseudo']) && !empty($_POST['pwd'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']);
        $password = $_POST['pwd'];
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE userName = ?');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['nickName'] = $pseudo;
            $_SESSION['pwd'] = $password;
            $_SESSION['roles'] = $user['id_role'];

        } else {
            echo "<div class='alert alert-danger' role='alert'>Pseudo ou mot de passe incorrect</div>";
        }
    } else {
        echo "<div class='alert alert-danger' role='alert'>Veuillez compléter tous les champs</div>";
    }
}

// Déconnexion de l'utilisateur
if (isset($_POST['disconnect'])) {
    session_destroy();
    header('Location: connexion.php');
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <title>Se connecter - Balance Ton Bully</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Liens vers les styles CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
    </style>
</head>

<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mx-auto mt-5">
        <div class="blue-bg p-4 rounded-lg shadow-lg">
        <h2 class="text-center text-white">Se connecter</h2>
        <?php if (!isset($_SESSION['nickName'])) { ?>
            <div id="wrapper" class="mt-5">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label text-white">Votre pseudo:</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo...">
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label text-white">Votre mot de passe:</label>
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe...">
                    </div>
                    <button type="submit" class="btn btn-primary text-white" name="submit">Se connecter</button>
                    <p class="text-center mt-3 text-white">Je n'ai pas encore de compte. <a href="Inscription.php">S'inscrire</a></p>
                </form>
            </div>
        <?php } else { ?>
            <p class="text-center">Vous êtes déjà connecté</p>
            <form method="post" action="">
                <button type="submit" class="btn btn-danger" name="disconnect">Déconnexion</button>
            </form>
        <?php } ?>
        </div>
</div>
</body>

</html>