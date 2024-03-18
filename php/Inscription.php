<?php
// Inclusion du fichier de fonctions et démarrage de la session
include './tools/functions.php';

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
            // Insertion des données dans la base de données
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (mail, password, userName) VALUES (?,?,?)');
            $stmt->execute([$mail, $hashedPassword, $pseudo]);
            //echo "<div class='alert alert-success' role='alert'>Utilisateur enregistré avec succès</div>";
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
    <title>S'inscrire - MovEase</title>
    <!-- Liens vers les styles CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tailwindcss/2.2.19/tailwind.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="stylesheet" href="CSS/login_authentification.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
    </style>
</head>

<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mx-auto my-5 vh-100">
    <div class="row justify-content-center">
        <div class="col-sm-10 col-md-10 col-lg-6 blue-bg p-4 rounded-lg shadow-lg">
            <h2 class="text-center text-white">S'inscrire</h2>
            <<?php
            if(isset($_POST['submit'])){
                if(empty($_POST['pseudo']) || empty($_POST['mail']) || empty($_POST['pwd'])){ ?>
                    <div class="alert alert-danger" role="alert">
                        Veuillez compléter tous les champs
                    </div>
                    <?php } elseif (isset($e)) { ?>
                    <div class="alert alert-danger" role="alert">
                        Erreur: <?php echo $e->getMessage(); ?>
                    </div>
                    <?php } else { ?>
                    <div class="alert alert-success" role="alert">
                        Utilisateur enregistré avec succès
                    </div>
                    <?php
                }
            } ?>

            <form method="POST" action="">
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
</div>
<?php include('../includes/footer.php') ?>
</body>

</html>