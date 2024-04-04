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

include('../php/tools/functions.php'); // Inclusion du fichier de fonctions
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

/**
 * Affiche les rendez-vous médicaux de l'utilisateur.
 *
 * Cette fonction récupère et affiche les rendez-vous médicaux de l'utilisateur
 * en cours à partir de la base de données. Elle distingue les rendez-vous passés
 * et à venir et permet à l'utilisateur d'annuler les rendez-vous à venir.
 *
 * @param PDO $dbConnect La connexion à la base de données.
 * @param int $userId L'identifiant de l'utilisateur pour lequel afficher les rendez-vous.
 * @return string HTML représentant la liste des rendez-vous.
 */
function afficherRendezVous($dbConnect, $userId) {
    $stmt = $dbConnect->prepare("
        SELECT rv.*, ps.nom, ps.prenom, ps.profession, ps.adresse, ps.ville, ps.code_postal 
        FROM rendez_vous rv 
        JOIN professionnels_sante ps ON rv.professionnel_id = ps.id 
        WHERE rv.utilisateur_id = ? 
        ORDER BY rv.date_heure
    ");
    $stmt->execute([$userId]);
    $rendezVous = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rendezVous)) {
        return '<div class="alert alert-info mt-4">Vous n\'avez aucun rendez-vous médical programmé.</div>';
    } else {
        $html = '<h2 class="text-center mt-4 mb-3">Vos Rendez-Vous Médicaux</h2><div class="list-group">';
        $html = '<div class="text-center mt-4 mb-2">';
        $html .= '<button id="toggleRdvFilter" class="btn btn-info">Afficher/Masquer les RDV Passés</button>';
        $html .= '</div>';
        foreach ($rendezVous as $rdv) {
            $rdvDate = new DateTime($rdv['date_heure']);
            $now = new DateTime();
            $rdvStatus = $rdvDate < $now ? 'Passé' : 'À venir';

            $html .= '<div class="list-group-item list-group-item-action bg-white mb-2 text-black p-2">';
            $html .= '<div class="d-flex justify-content-between">';
            $html .= '<div>';
            $html .= "<div><strong>{$rdv['nom']} {$rdv['prenom']}</strong> - {$rdv['profession']}</div>";
            $html .= "<div>{$rdv['adresse']}, {$rdv['ville']}, {$rdv['code_postal']}</div>";
            $html .= "<div>Date et Heure : " . $rdvDate->format('d/m/Y H:i') . "</div>";
            $html .= '</div>';

            $html .= '<div>';
            $html .= "<div class='badge bg-" . ($rdvStatus == 'Passé' ? 'secondary' : 'primary') . "'>$rdvStatus</div>";

            if ($rdvStatus == 'À venir') {
                $html .= "<form method='post' action='account.php' class='mt-2'>";
                $html .= "<input type='hidden' name='rdvId' value='{$rdv['id']}'>";
                $html .= "<input type='submit' class='btn btn-danger btn-sm' value='Annuler'>";
                $html .= "</form>";
            }
            $html .= '</div>';
            $html .= '</div>';
            $html .= '</div>';
        }
        $html .= '</div>';
        return $html;
    }
}

if (isset($_POST['rdvId'])) {
    $stmt = $dbConnect->prepare('DELETE FROM rendez_vous WHERE id = ?');
    $stmt->execute([$_POST['rdvId']]);

    header('Location: account.php?message=RDV annulé');
    exit();
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
                <input type="text" class="form-control" id="inputName" value="<?php echo $user['name'] ?>" name="newName">
            </div>
            <div class="col-sm-2">
                <button type="submit" class="btn btn-primary" id="newName" name="modName">Modifier</button>
            </div>
        </div>
        <div class="row mb-3">
            <label for="newFName" class="col-sm-2 col-form-label">Prénom :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newFName" value="<?php echo $user['firstName'] ?>" name="newFName">
            </div>
        </div>
        <div class="row mb-3">
            <label for="newUName" class="col-sm-2 col-form-label">Nom d'utilisateur :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newUName" value="<?php echo $user['userName'] ?>" name="newUName">
            </div>
        </div>
        <div class="row mb-3">
            <label for="newMail" class="col-sm-2 col-form-label">Adresse mail :</label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="newMail" value="<?php echo $user['mail'] ?>" name="newMail">
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
                   /* $stmt = $dbConnect->prepare('SELECT id_role FROM utilisateurs WHERE userName = ?');
                    $stmt->execute(array($_SESSION['nickName']));
                    $userRole = $stmt->fetchColumn();*/

                    // Vérifiez d'abord si le rôle de l'utilisateur a été récupéré avec succès
                    //if ($userRole !== false){
                        // Utilisez le rôle de l'utilisateur pour déterminer s'il est un administrateur
                        if ($_SESSION['id_role'] == 1) {
                            // Afficher des fonctionnalités spécifiques pour les utilisateurs avec le rôle "Admin"
                            echo '<a href="profilAdmin.php" class="btn btn-success">Admin</a>';
                        }
                    //} else {
                        // Gérer le cas où le rôle de l'utilisateur n'a pas pu être récupéré de la base de données
                      //  echo "Erreur : Impossible de récupérer le rôle de l'utilisateur depuis la base de données.";
                    //}
                ?>
            </div>
        </div>
    </form>
    <?php
    echo afficherRendezVous($dbConnect, $userId);
    ?>
</div>
<?php include('../includes/footer.php') ?>
<script src="../js/userMod.js"></script>
<script>
    document.getElementById('toggleRdvFilter').addEventListener('click', function() {
        let rendezVousItems = document.querySelectorAll('.list-group-item');
        rendezVousItems.forEach(function(item) {
            if (item.querySelector('.badge.bg-secondary')) { // RDV Passé
                item.classList.toggle('d-none');
            }
        });
    });
</script>
</body>
</html>