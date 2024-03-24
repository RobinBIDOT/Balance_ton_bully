<?php
include 'tools/functions.php';
$dbConnexion = dbConnexion();
session_start();
@$mail = $_POST['mailRetrieve'];
if (isset($_POST['sendMail'])) {
    $stmt1 = $dbConnexion->prepare('SELECT * FROM utilisateurs WHERE mail = ?');
    $stmt1->execute([$mail]);
    $user = $stmt1->fetch(PDO::FETCH_ASSOC);
    if ($user['mail']) {
        $token = uniqid();
        $url = 'http://localhost/balance_ton_bully/php/renewPwd.php?token=' . $token;
        $message = "Bonjour, voici le lien pour reinitialiser votre mot de passe " . $url;
        $update = $dbConnexion->prepare('UPDATE utilisateurs SET token = ? WHERE mail = ?');
        $update->execute([$token, $mail]);
        mail($user['mail'], "Mot de passe oublié", $message, 'From: Balance ton Bully' );
        echo '<div class="alert alert-success" role="alert">Mail envoyé !</div>';
    } else {
        echo '<div class="alert alert-warning" role="alert">Veuillez renseigner une adresse valide</div>';
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
    <title>Récuperation Mot de Passe - Balance Ton Bully</title>
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
    </style>
</head>
<body>
    <?php include '../includes/headerNav.php'?>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body blue-bg p-4 shadow-lg rounded text-white">
                        <h2 class="card-title text-center">Mot de passe oublié</h2>
                        <h6 class="text-center">Vous allez recevoir un mail à l'adresse que vous allez renseigner si un compte y est rattaché</h6>
                        <form method="post" class="mt-4">
                            <div class="form-group">
                                <label for="mail">Adresse Mail</label>
                                <input type="email" class="form-control my-2" name="mailRetrieve" placeholder="Entrez votre mail...">
                            </div>
                            <button type="submit" name="sendMail" class="btn btn-primary my-2">Envoyer</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include '../includes/footer.php'?>