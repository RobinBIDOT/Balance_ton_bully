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
    <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link href="../css/styleRenewPwd.css" rel="stylesheet">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Changement de mot de passe</title>
</head>
<body>
    <?php include('../includes/headerNav.php') ?>

        <div class="container my-5">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="bg-custom">
                        <h1 class="text-center text-white mb-4">Récuperation de mot de passe</h1>
                        <form method="post">
                                <label for="newPwd" class="form-label custom-form-label text-white">Nouveau mot de passe :</label>
                            <div class="mb-3 custom-form-input-group">
                                <input type="password" class="form-control custom-form-input" name="newPwd" placeholder="Nouveau mot de passe">
                                <i class="fas fa-lock custom-icon"></i>
                            </div>
                            <div class="mb-3 custom-form-input-group">
                                <input type="password" class="form-control custom-form-input" name="verifPwd" placeholder="Confirmez le mot de passe">
                                <i class="fas fa-lock custom-icon"></i>
                            </div>
                            <div class="d-grid gap-2 col-6 mx-auto">
                                <button type="submit" class="btn btn-primary text-white" name="submit" value="Valider">Valider</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

    <?php include('../includes/footer.php') ?>
</body>
</html>
<?php } else{
    echo "Vous n'êtes pas autorisé à accéder a cette page.";
}