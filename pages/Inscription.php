<?php
/**
 * Script d'inscription pour les utilisateurs.
 *
 * Ce script permet aux nouveaux utilisateurs de s'inscrire en fournissant leurs informations.
 * Il inclut la validation des données du formulaire et l'insertion des données dans la base de données.
 *
 * PHP version 7.4
 *
 * @category Authentication
 * @package  MovEase
 */

// Inclusion du fichier de fonctions et démarrage de la session
include '../php/tools/functions.php';

// Démarrage ou reprise de la session
session_start();

// Connexion à la base de données
$pdo = dbConnexion();

// Vérification de la soumission du formulaire d'inscription
if (isset($_POST['submit'])) {
    // Vérification de la saisie des champs requis
    if (empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['pwd'])) {
        $errors = array();
        /* if (empty($_POST['name'])) {
            $errors[] = "Veuillez saisir votre prénom";
        }
        if (empty($_POST['lastName'])) {
            $errors[] = "Veuillez saisir votre nom";
        }*/
        if (empty($_POST['pseudo'])) {
            $errors[] = "Veuillez saisir votre pseudo";
        }
        if (empty($_POST['mail'])) {
            $errors[] = "Veuillez saisir votre adresse mail";
        }
        if (empty($_POST['pwd'])) {
            $errors[] = "Veuillez saisir un mot de passe";
        }
        /*if (empty($_POST['fonction'])) {
            $errors[] = "Veuillez sélectionner votre fonction";
        }*/
        // Vérifier si les mots de passe correspondent
        if (!empty($_POST['pwd']) && ($_POST['pwd'] != $_POST['pwd_confirm'])) {
            $errors[] = "Les mots de passe ne correspondent pas";
        }
        // Vérifier si l'utilisateur est un professionnel de la santé
        $isProfessional = isset($_POST['is_professional']) ? true : false;

        // Affichage des erreurs
        foreach ($errors as $error) {
            echo "<div class='alert alert-danger' role='alert'>$error</div>";
        }
    } else {
        // Récupération des données saisies dans le formulaire
        $mail = $_POST['mail'];
        $password = $_POST['pwd'];
        $pseudo = $_POST['pseudo'];


        // Hashage du mot de passe
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE mail = ? OR userName = ?');
            $stmt->execute([$mail, $pseudo]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                if ($user['mail'] == $mail) {
                    echo "<div class='alert alert-warning' role='alert'>Un compte est déjà associé à cette adresse e-mail</div>";
                } elseif ($user['userName'] == $pseudo) {
                    echo "<div class='alert alert-warning' role='alert'>Le nom d'utilisateur n'est pas disponible</div>";
                }
            } else {
                // Insertion des données dans la base de données
                $stmt = $pdo->prepare('INSERT INTO utilisateurs (mail, password, userName) VALUES (?,?,?)');
                $stmt->execute([$mail, $hashedPassword, $pseudo]);
                echo "<div class='alert alert-success' role='alert'>Utilisateur enregistré avec succès</div>";
            }
        } catch (PDOException $e) {
            echo "<div class='alert alert-danger' role='alert'>Erreur: " . $e->getMessage() . "</div>";
        }

    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>S'inscrire - Balance Ton Bully</title>
    <!-- Liens vers les styles CSS -->
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="CSS/login_authentification.css">
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
        .full-height {
            min-height: 100vh; 
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<!-- <div class="container full-height"> -->
    <div class="d-flex justify-content-center align-items-center vh-100">
        <div class="col-md-6 col-lg-4 blue-bg p-4 shadow-lg rounded">

            <h2 class="text-center text-white">S'inscrire</h2>
            <?php
            if(isset($_POST['submit'])){
                if(empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['pwd'])){ ?>
                    <div class="alert alert-danger" role="alert">
                        Veuillez compléter tous les champs
                    </div>
                    <?php } elseif (isset($e)) { ?>
                    <div class="alert alert-danger" role="alert">
                        Erreur: <?php echo $e->getMessage(); ?>
                    </div>
                    <?php }
            } ?>
            <form method="POST">
                <!--<div class="mb-3">
                    <label for="name" class="form-label text-white">Votre prénom:</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Nom...">
                </div>
                <div class="mb-3">
                    <label for="lastName" class="form-label text-white">Votre nom:</label>
                    <input type="text" class="form-control" id="lastName" name="lastName" placeholder="Nom de famille...">
                </div> -->
                <div class="mb-3">
                    <label for="pseudo" class="form-label text-white">Votre pseudo:</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="Pseudo...">
                </div>
                <div class="mb-3">
                    <label for="mail" class="form-label text-white">Votre adresse mail:</label>
                    <input type="email" class="form-control" id="mail" name="mail" placeholder="E-mail...">
                </div>
                <!--<div class="mb-3">
                    <label for="fonction" class="form-label text-white">Votre fonction:</label>
                    <input type="text" class="form-control" id="fonction" name="fonction" placeholder="Fonction...">
                </div> -->
                <div class="mb-3">
                    <label for="pwd" class="form-label text-white">Votre mot de passe:</label>
                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Mot de passe...">
                </div>
                <div class="mb-3">
                    <label for="pwd_confirm" class="form-label text-white">Confirmez votre mot de passe:</label>
                    <input type="password" class="form-control" id="pwd_confirm" name="pwd_confirm" placeholder="Confirmez le mot de passe...">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="is_professional" name="is_professional">
                    <label class="form-check-label text-white" for="is_professional">Êtes-vous un professionnel de santé ?</label>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <button type="submit" class="btn btn-primary" name="submit">S'enregistrer</button>
                </div>
                <div class="d-flex justify-content-center align-items-center">
                    <p class="text-white mt-3">J'ai déjà un compte. <a href="connexion.php" class="text-white font-bold">Se connecter</a></p>
                </div>
            </form>
        </div>
    </div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>