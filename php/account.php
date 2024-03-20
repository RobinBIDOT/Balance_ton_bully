<?php
include('tools/functions.php');
$dbConnect = dbConnexion();
session_start();


$stmt = $dbConnect -> prepare('SELECT * FROM utilisateurs WHERE userName = ?');
$stmt->execute(array($_SESSION['nickName']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

@$newName = $_POST['newName'];
@$newFName = $_POST['newFName'];
@$newUName = $_POST['newUName'];
@$newMail = $_POST['newMail'];
@$userId = $user['id'];

// Déconnexion de l'utilisateur
if (isset($_POST['disconnect'])) {
    session_destroy();
    header('Location: connexion.php');
}
if (isset($_POST['delete'])) {
echo '<div class="modal" id="alertModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alerte</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>Êtes-vous sûr de vouloir supprimer votre compte ?</p>
                </div>
                <div class="modal-footer">
                    <form action="" method="post">
                        <input type="submit" class="btn btn-secondary" data-bs-dismiss="modal" value="Annuler">
                        <input type="submit" class="btn btn-danger" name="suppr" value="Supprimer"  >
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let myModal = new bootstrap.Modal(document.getElementById("alertModal"));
            myModal.show();
        });</script>';
}

if(isset($_POST['suppr'])){
    $stmt = $dbConnect->prepare('DELETE FROM utilisateurs WHERE id = ?');
    $stmt -> execute([$userId]);
    session_destroy();
    header('Location: connexion.php');
    exit();
}

if(isset($_POST['modName'])){
    $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET name = ? WHERE id = ?');
    $stmt->execute([$newName, $userId]);
    $user['name'] = $_POST['newName'];
}
if(isset($_POST['modFName'])){
    $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET firstName = ? WHERE id = ?');
    $stmt->execute([$newFName, $userId]);
}
if(isset($_POST['modUName'])){
    $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET userName = ? WHERE id = ?');
    $stmt->execute([$newUName, $userId]);
}
if(isset($_POST['modMail'])){
    $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET mail = ? WHERE id = ?');
    $stmt->execute([$newMail, $userId]);
}
?>


<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mon compte</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
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
            <h2 class="text-center text-white">Mon compte</h2>
            <form method="post" action="account.php">
                <div class="mb-3">
                    <label for="newName" class="form-label text-white">Nom :</label>
                    <input type="text" class="form-control" id="newName" name="newName" value="<?php echo $user['name']; ?>">
                    <button type="submit" class="btn btn-primary text-white mt-2" name="modName">Modifier</button>
                </div>
                <div class="mb-3">
                    <label for="newFName" class="form-label text-white">Prénom :</label>
                    <input type="text" class="form-control" id="newFName" name="newFName" value="<?php echo $user['firstName']; ?>">
                    <button type="submit" class="btn btn-primary text-white mt-2" name="modFName">Modifier</button>
                </div>
                <div class="mb-3">
                    <label for="newUName" class="form-label text-white">Nom d'utilisateur :</label>
                    <input type="text" class="form-control" id="newUName" name="newUName" value="<?php echo $user['userName']; ?>">
                    <button type="submit" class="btn btn-primary text-white mt-2" name="modUName">Modifier</button>
                </div>
                <div class="mb-3">
                    <label for="newMail" class="form-label text-white">Adresse mail :</label>
                    <input type="email" class="form-control" id="newMail" name="newMail" value="<?php echo $user['mail']; ?>">
                    <button type="submit" class="btn btn-primary text-white mt-2" name="modMail">Modifier</button>
                </div>
                    <div class="d-flex justify-content-between mt-4">
                    <button type="submit" class="btn btn-danger" name="disconnect">Déconnexion</button>
                    <button type="submit" class="btn btn-danger" name="delete">Supprimer Compte</button>
                </div>

            </form>

        </div>
    </div>
    <?php include('../includes/footer.php') ?>
</body>
</html>