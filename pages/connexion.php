<?php
/**
 * Script de connexion pour les utilisateurs.
 *
 * Ce script permet aux utilisateurs de se connecter en utilisant leur pseudo et mot de passe.
 * Il gère également la déconnexion des utilisateurs connectés.
 *
 * PHP version 7.4
 *
 * @category Authentication
 * @package  BalanceTonBully
 */

// Inclusion du fichier de fonctions et démarrage de la session
include '../php/tools/functions.php';
session_start();


// Connexion à la base de données
$pdo = dbConnexion();
if (isset($_GET['success']) && $_GET['success'] == '1') {
    echo '<div class="alert alert-success" role="alert">Mot de passe réinitialisé avec succès !</div>';
}

// Traitement du formulaire de connexion
if (isset($_POST['submit'])) {
    // Vérification des champs du formulaire
    if (!empty($_POST['pseudo']) && !empty($_POST['pwd'])) {
        $pseudo = htmlspecialchars($_POST['pseudo']); // Nettoyage du pseudo
        $password = $_POST['pwd']; // Récupération du mot de passe

        // Requête pour vérifier l'existence de l'utilisateur
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE userName = ?');
        $stmt->execute([$pseudo]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Vérification du mot de passe
        if ($user && password_verify($password, $user['password'])) {

            // Enregistrement des informations de l'utilisateur dans la session
            $_SESSION['nickName'] = $pseudo;
            $_SESSION['pwd'] = $password;
            $_SESSION['id'] = $user['id'];

            $_SESSION['id_role'] = $user['id_role'];
            header('Location: index.php');


            // Vérification du mot de passe
            if ($user && password_verify($password, $user['password'])) {
                // Enregistrement des informations de l'utilisateur dans la session
                $_SESSION['nickName'] = $pseudo;
                $_SESSION['pwd'] = $password;
                $_SESSION['id'] = $user['id'];
                $_SESSION['id_role'] = $user['id_role'];
            } else {
                // Affichage d'un message d'erreur en cas de pseudo ou mot de passe incorrect
                echo "<div class='alert alert-danger' role='alert'>Pseudo ou mot de passe incorrect</div>";
            }
        } else {
            // Affichage d'un message d'erreur si tous les champs ne sont pas remplis
            echo "<div class='alert alert-danger' role='alert'>Veuillez compléter tous les champs</div>";

        }
    } else {
        // Affichage d'un message d'erreur si tous les champs ne sont pas remplis
        echo "<div class='alert alert-danger' role='alert'>Veuillez compléter tous les champs</div>";
    }
}

// Traitement de la déconnexion de l'utilisateur
if (isset($_POST['disconnect'])) {
    session_destroy(); // Destruction de la session
    header('Location: connexion.php'); // Redirection vers la page de connexion
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - Balance Ton Bully</title>
    <!-- Liens vers les styles CSS -->
    <?php include('../includes/headLink.php') ?>
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
    </style>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="col-md-6 col-lg-4 blue-bg p-4 shadow-lg rounded">
        <h2 class="text-center text-white">Se connecter</h2>
        <?php if (!isset($_SESSION['nickName'])) { ?>
            <div id="wrapper" class="mt-5">
                <form method="POST">
                    <div class="mb-3">
                        <label for="pseudo" class="form-label text-white">Votre pseudo:</label>
                        <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo...">
                    </div>
                    <div class="mb-3">
                        <label for="pwd" class="form-label text-white">Votre mot de passe:</label>
                        <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe...">
                    </div>

                    <button type="submit" class="btn btn-primary text-white" name="submit">Se connecter</button>
                    <p class="text-center mt-3 text-white">Je n'ai pas encore de compte. <a href="Inscription.php" class="text-white font-bold">S'inscrire</a></p>
                    <p class="text-center mt-3 text-white"><a href="retrievePwd.php" class="text-white font-bold">J'ai perdu mon mot de passe. </a></p>

                </form>
            </div>
        <?php } else { ?>
            <p class="text-center">Vous êtes déjà connecté</p>
            <div class="d-flex justify-content-center align-items-center">
                <form method="post" action="">
                    <button type="submit" class="btn btn-danger" name="disconnect">Déconnexion</button>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>