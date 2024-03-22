<?php
/**
 * Script de gestion du compte utilisateur.
 *
 * Ce script permet aux utilisateurs de modifier leurs informations personnelles
 * telles que le nom, le prénom, le nom d'utilisateur et l'adresse e-mail.
 * Il permet également la déconnexion et la suppression du compte.
 *
 * PHP version 7.4
 *
 * @category UserManagement
 * @package  BalanceTonBully
 */

include('tools/functions.php'); // Inclusion du fichier de fonctions
$dbConnect = dbConnexion(); // Connexion à la base de données
session_start(); // Démarrage de la session

// Récupération des informations de l'utilisateur connecté
$stmt = $dbConnect -> prepare('SELECT * FROM utilisateurs WHERE userName = ?');
$stmt->execute(array($_SESSION['nickName']));
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Déclaration des variables pour stocker les nouvelles informations
@$newName = $_POST['newName'];
@$newFName = $_POST['newFName'];
@$newUName = $_POST['newUName'];
@$newMail = $_POST['newMail'];
@$userId = $user['id'];

// Traitement de la déconnexion de l'utilisateur
if (isset($_POST['disconnect'])) {
    session_destroy(); // Destruction de la session
    header('Location: connexion.php'); // Redirection vers la page de connexion
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

// Traitement de la suppression du compte
if(isset($_POST['suppr'])){
    $stmt = $dbConnect->prepare('DELETE FROM utilisateurs WHERE id = ?');
    $stmt -> execute([$userId]);
    session_destroy(); // Destruction de la session après la suppression du compte
    header('Location: connexion.php'); // Redirection vers la page de connexion
    exit();
}

// Traitement de la modification des informations de l'utilisateur
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
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mt-5 mb-5 blue-background">
    <h1 class="text-center">Votre Profil</h1>
    <form method="post" action="account.php" class="mt-5">
        <div class="row mb-3">
            <label for="newName" class="col-sm-2 col-form-label">Nom :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newName" value="<?php echo $user['name'] ?>" name="newName">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary" name="modName">Modifier</button>
            </div>
        </div>
        <div class="row mb-3">
            <label for="newFName" class="col-sm-2 col-form-label">Prénom :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newFName" value="<?php echo $user['firstName'] ?>" name="newFName">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary" name="modFName">Modifier</button>
            </div>
        </div>
        <div class="row mb-3">
            <label for="newUName" class="col-sm-2 col-form-label">Nom d'utilisateur :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newUName" value="<?php echo $user['userName'] ?>" name="newUName">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary" name="modUName">Modifier</button>
            </div>
        </div>
        <div class="row mb-3">
            <label for="newMail" class="col-sm-2 col-form-label">Adresse mail :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newMail" value="<?php echo $user['mail'] ?>" name="newMail">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary" name="modMail">Modifier</button>
            </div>
        </div>

        <br><br>
        <div class="row mb-3">
            <div class="col-sm-12 d-flex justify-content-around">
                <!-- Bouton "Déconnexion" -->
                <button type="submit" class="btn btn-danger" name="disconnect">Déconnexion</button>
                <!-- Bouton "Supprimer Compte" -->
                <button type="submit" class="btn btn-danger" name="delete">Supprimer Compte</button>
                <!-- Bouton "Admin" (si l'utilisateur est un admin) -->
                <?php
                    // Vérification du rôle de l'utilisateur dans la base de données
                    $stmt = $dbConnect->prepare('SELECT id_role FROM utilisateurs WHERE userName = ?');
                    $stmt->execute(array($_SESSION['nickName']));
                    $userRole = $stmt->fetchColumn();

                    // Vérifiez d'abord si le rôle de l'utilisateur a été récupéré avec succès
                    if ($userRole !== false) {
                        // Utilisez le rôle de l'utilisateur pour déterminer s'il est un administrateur
                        if ($userRole == 1) {
                            // Afficher des fonctionnalités spécifiques pour les utilisateurs avec le rôle "Admin"
                            // Par exemple :
                            echo '<a href="../pages/profilAdmin.php" class="btn btn-success">Admin</a>';
                        }
                    } else {
                        // Gérer le cas où le rôle de l'utilisateur n'a pas pu être récupéré de la base de données
                        echo "Erreur : Impossible de récupérer le rôle de l'utilisateur depuis la base de données.";
                    }
                ?>
            </div>
        </div>
    </form>
</div>
<?php include('../includes/footer.php') ?>
</body>
</html>