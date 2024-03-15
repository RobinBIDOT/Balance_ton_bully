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
var_dump($user["role"]);
if(isset($_POST['modName'])){
    $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET name = ? WHERE id = ?');
    $stmt->execute([$newName, $userId]);
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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mon compte</title>
</head>
<body>
    <?php include('../includes/headerNav.php') ?>
    <form method="post" action="account.php">
        <p>Nom :</p>
        <input type="text" value="<?php echo $user['name'] ?>" name="newName"> <input type="submit" value="modifier" name="modName" >
        <p>Prenom :</p>
        <input type="text" value="<?php echo $user['firstName'] ?>" name="newFName"><input type="submit" value="modifier" name="modFName">
        <p>Nom d'utilisateur :</p>
        <input type="text" value="<?php echo $user['userName'] ?>" name="newUName"><input type="submit" value="modifier" name="modUName" >
        <p>Adresse mail :</p>
        <input type="text" value="<?php echo $user['mail'] ?>" name="newMail"><input type="submit" value="modifier" name="modMail">
        <?php if (isset($_SESSION['nickName'])) { ?> <br> <br>
        <button type="submit" class="btn btn-danger" name="disconnect">Déconnexion</button>
        <button type="submit" class="btn btn-danger" name="delete">Supprimer Compte</button>
        <?php } ?>
    </form>
    <?php include('../includes/footer.php') ?>
</body>
</html>