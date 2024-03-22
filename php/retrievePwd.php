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
        mail($user['mail'], "Mot de passe oublié", $message );
        echo "Mail envoyé";
    } else {
        echo "Veuillez renseigner une adresse mail valide";
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
</head>
<body>
    <?php include '../includes/headerNav.php'?>
    <h2>Mot de passe oublié</h2>
    <h6>Vous allez recevoir un mail à l'adresse que vous allez renseignez si un compte y est rattaché</h6>
    <form method="post">
        <label for="mail"> Mail
            <input type="email" name="mailRetrieve" placeholder="mail...">
            <input type="submit" name ="sendMail" value="Envoyer">
        </label>
    </form>
    <?php include '../includes/footer.php'?>
</body>
</html>
