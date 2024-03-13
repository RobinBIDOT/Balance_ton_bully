<?php
include 'c:/xampp/htdocs/Balance_ton_bully/php/tools/functions.php';

session_start();
$pdo = dbConnexion();

if (isset($_POST['submit'])){
    if (!empty($_POST['nickName']) AND !empty($_POST['pwd'])) {
        $nickName = htmlspecialchars($_POST['nickName']);
        $password = $_POST['pwd'];
        $stmt = $pdo->prepare('SELECT * FROM utilisateurs WHERE userName = ?');
        $stmt->execute([$nickName]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if($user && password_verify($password, $user['password'])){
            $_SESSION['nickName'] = $nickName;
            $_SESSION['pwd'] = $password;
            $_SESSION['id'] = $user['id'];

        }else{
            echo"nom d'utilisateur ou mot de passe incorrect";
        }
    }else{
        echo'veuillez completer tous les champs';
    }
}
if (isset($_POST['disconnect'])){
    session_destroy();
    header('Location: connexion.php');
}


?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <title>Se connecter - Balance Ton Bully</title>
</head>
<body>
<?php include('../includes/headerNav.php')?>
<h2>Se connecter</h2>
<?php if (!isset($_SESSION['nickName'])){?>
<div id="wrapper">
    <form method="POST" action="">
        <p>Votre nom d'utilisateur: <br> <input type="text" name="nickName" id="nickName" placeholder="Nom d'utilisateur..."></p>
        <p>Votre mot de passe: <br> <input type="password" name="pwd" id="pwd" placeholder="Mot de passe..."></p>
        <a href="inscription.php">Vous n'avez pas de compte ?</a><br>
        <input type="submit" name="submit" id="submit" value="se connecter">
    </form>
    <?php }else{ ?>
        <p>Vous êtes déjà connecté</p>
        <form method="post" action="">
            <button name="disconnect">deconnexion</button>
        </form>
    <?php } ?>
</div>
</body>
</html>