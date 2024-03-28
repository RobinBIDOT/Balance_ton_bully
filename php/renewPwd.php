<?php
include 'tools/functions.php';
$dbConnexion = dbConnexion();
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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Changement de mot de passe</title>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<h1>Récuperation de mot de passe</h1>
<form method="post">
    <label for="newPwd">Nouveau mot de passe :</label>
    <input type="password" name="newPwd">
    <input type="password" name="verifPwd">
    <input type="submit" name="submit" value="valider">

</form>
<?php include('../includes/footer.php') ?>
</body>
</html>
<?php } else{
    echo "Vous n'êtes pas autorisé à accéder a cette page.";
}