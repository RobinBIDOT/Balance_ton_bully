<?php
session_start();
include 'c:/xampp/htdocs/Balance_ton_bully/php/tools/functions.php';


$pdo = dbConnexion();

if(isset($_POST['submit'])) {
    if (empty($_POST['name']) or empty($_POST['lastName']) or empty($_POST['nickName']) or empty($_POST['mail']) or empty($_POST['pwd'])) {

        if (empty($_POST['name'])) {
            echo "Veuillez saisir votre prénom<br>";
        }
        if (empty($_POST['lastName'])) {
            echo "Veuillez saisir votre nom<br>";
        }
        if (empty($_POST['nickName'])) {
            echo "Veuillez saisir votre nom d'utilisateur<br>";
        }
        if (empty($_POST['mail'])) {
            echo "Veuillez saisir votre adresse mail<br>";
        }
        if (empty($_POST['pwd'])) {
            echo "Veuillez saisir un mot de passe<br>";
        }
    } else {
        @$name = $_POST['name'];
        @$lastName = $_POST['lastName'];
        @$mail = $_POST['mail'];
        @$password = $_POST['pwd'];
        @$nickName = $_POST['nickName'];

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare('INSERT INTO utilisateurs (name, firstName, mail, password, userName) VALUES (?,?,?,?,?)');
            $stmt->execute([$lastName, $name, $mail, $hashedPassword, $nickName]);
            echo "Utilisateur enregistré avec succès";
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }

    }
}

?>




<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="CSS/login_authentification.css" id="css">
    <link rel="stylesheet" href="CSS/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <title>S'inscrire - MovEase</title>
</head>
<body>
<?php if (!isset($_SESSION['connexion'])){ ?>
    <h2>S'inscrire</h2>
    <div id="wrapper">
        <form method="POST" action="">
            <p>Votre prénom: <br> <input type="text" name="name" id="name" placeholder="Nom..."></p>
            <p>Votre nom: <br> <input type="text" name="lastName" id="lastName" placeholder="Nom de famille..."></p>
            <p>Votre adresse mail: <br> <input type="email" name="mail" id="mail" placeholder="E-mail..."></p>
            <p>Votre nom d'utilisateur: <br> <input type="text" name="nickName" id="nickName" placeholder="Nom d'utilisateur..."></p>
            <p>Votre mot de passe: <br> <input type="password" name="pwd" id="pwd" placeholder="Mot de passe..."></p>
            <a href="connexion.php">Vous avez déjà un compte ?</a><br>
            <input type="submit" name="submit" id="submit" value="s'enregistrer">
        </form>
    </div>
<?php }else{ ?>
    <p>Vous êtes déjà connecté</p>
    <button>deconnexion</button>
<?php } ?>
</body>
</html>