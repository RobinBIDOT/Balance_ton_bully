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



/**
 * Génère et retourne le HTML du profil professionnel de santé.
 *
 * Cette fonction récupère les informations, expertises, horaires et rendez-vous d'un professionnel
 * de santé et retourne une chaîne de caractères contenant le HTML pour afficher ces informations.
 *
 * @param PDO $dbh La connexion à la base de données.
 * @param int $userId L'identifiant de l'utilisateur pour lequel afficher le profil.
 * @return string HTML représentant le profil professionnel de santé.
 */
function afficherProfilProfessionnelSante($dbh, $userId) {
    $stmt = $dbh->prepare("SELECT * FROM professionnels_sante WHERE utilisateur_id = ?");
    $stmt->execute([$userId]);
    $profil = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$profil) {
        return " ";
    }

    // Ajoutez le champ 'duree_rdv' à la liste des champs récupérés de la base de données
    $stmtExpertise = $dbh->prepare("SELECT e.nom FROM expertise e JOIN professionnel_expertise pe ON e.id = pe.expertise_id WHERE pe.professionnel_id = ?");
    $stmtExpertise->execute([$profil['id']]);
    $expertises = $stmtExpertise->fetchAll(PDO::FETCH_ASSOC);

    $stmtHoraires = $dbh->prepare("SELECT * FROM horaires_professionnels WHERE professionnel_id = ?");
    $stmtHoraires->execute([$profil['id']]);
    $horaires = $stmtHoraires->fetchAll(PDO::FETCH_ASSOC);

    // Récupération des rendez-vous du professionnel de santé
    $stmtRdv = $dbh->prepare("
        SELECT rv.*, CONCAT(u.firstName, ' ', u.name) AS client_name
        FROM rendez_vous rv
        JOIN utilisateurs u ON rv.utilisateur_id = u.id
        WHERE rv.professionnel_id = ?
        ORDER BY rv.date_heure
    ");
    $stmtRdv->execute([$profil['id']]);
    $rendezVous = $stmtRdv->fetchAll(PDO::FETCH_ASSOC);

    $expertisesString = join(', ', array_map(function($e) { return htmlspecialchars($e['nom']); }, $expertises));

    $htmlHoraires = "<div class='mt-5'>
                    <h3>Horaires</h3>
                    <form method='post' action='modifierHoraires.php'> 
                        <!-- Tableau des horaires -->
                        <table class='table table-bordered'>
                            <thead>
                                <tr>
                                    <th>Jour</th>
                                    <th>Matin</th>
                                    <th>Après-midi</th>
                                </tr>
                            </thead>
                            <tbody>";
    foreach ($horaires as $horaire) {
        $htmlHoraires .= "<tr>
                        <td>" . htmlspecialchars($horaire['jour_semaine']) . "</td>
                        <td>" . htmlspecialchars($horaire['heure_debut_matin']) . ' - ' . htmlspecialchars($horaire['heure_fin_matin']) . "</td>
                        <td>" . htmlspecialchars($horaire['heure_debut_apres_midi']) . ' - ' . htmlspecialchars($horaire['heure_fin_apres_midi']) . "</td>
                    </tr>";
    }
    $htmlHoraires .= "</tbody></table></form></div>";


    // Génération du HTML pour les rendez-vous
    $htmlRendezVous = "<div class='mt-5'>";
    foreach ($rendezVous as $rdv) {
        $rdvDate = new DateTime($rdv['date_heure']);
        $now = new DateTime();
        $rdvStatus = $rdvDate < $now ? 'Passé' : 'À venir';

        // Générer une classe CSS pour distinguer les rendez-vous passés et à venir
        $statusClass = $rdvStatus == 'Passé' ? 'bg-secondary rdvPasse' : 'bg-primary';

        // Générer le HTML pour chaque rendez-vous
        $htmlRendezVous .= "<div class='list-group-item list-group-item-action mb-2 listeRdvPro' style='background-color: white; color: black; padding: 10px;'>
                            <div class='d-flex justify-content-between'>
                                <div>
                                    <div><strong>Date et Heure :</strong> " . $rdvDate->format('d/m/Y H:i') . "</div>
                                    <div><strong>Client :</strong> " . htmlspecialchars($rdv['client_name']) . "</div>
                                </div>
                                <div style='margin-right: 10px;'>
                                    <div class='badge $statusClass'>$rdvStatus</div>";
        if ($rdvStatus == 'À venir') {
            $htmlRendezVous .= "<form method='post' action='account.php' class='mt-2'>
                                <input type='hidden' name='rdvId' value='{$rdv['id']}'>
                                <input type='submit' class='btn btn-danger btn-sm' value='Annuler'>
                            </form>";
        }
        $htmlRendezVous .= "</div>
                        </div>
                    </div>";
    }
    $htmlRendezVous .= "</div>";

    if ($profil) {
        $html = "<div class='container mt-5'>
                <div class='row mt-4'>
                    <div class='col-md-4'><img src='" . htmlspecialchars($profil['photo']) . "' class='img-fluid rounded' style='max-width: 200px; background-color: white;' alt='Photo de profil'></div>
                    <div class='col-md-8'>
                        <p><strong>Profession :</strong> " . htmlspecialchars($profil['profession']) . "</p>
                        <p><strong>Adresse :</strong> " . htmlspecialchars($profil['adresse']) . ', ' . htmlspecialchars($profil['ville']) . ' ' . htmlspecialchars($profil['code_postal']) . "</p>
                        <p><strong>Présentation :</strong> " . nl2br(htmlspecialchars($profil['presentation'])) . "</p>
                        <p><strong>Expertises :</strong> $expertisesString</p>
                    </div>
                </div>
                $htmlHoraires
                <div class='mt-5'>
                    <h3>Rendez-vous</h3>
                    <!--<div class='text-center'> 
                        <button id='toggleRdvFilterProfessionnel' class='btn btn-info' style='margin-bottom: 10px;'>Afficher/Masquer les RDV Passés</button>
                    </div>-->
                    $htmlRendezVous
                </div>
            </div>";
        return $html;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon compte</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mt-5 mb-5 blue-background">
    <h1 class="text-center" style="font-size: 2rem; font-weight: bold;">Votre Profil</h1>
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
    if ($_SESSION['id_role'] == 2) {
        echo afficherRendezVous($dbConnect, $userId);
    }
    if (isset($userId)) {
        echo afficherProfilProfessionnelSante($dbConnect, $userId);
    }
    ?>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
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

    // document.addEventListener("DOMContentLoaded", function() {
    //     document.body.addEventListener('click', function(event) {
    //         if(event.target.id === 'toggleRdvFilterProfessionnel') {
    //             let rendezVousItems = document.querySelectorAll('.listeRdvPro');
    //             rendezVousItems.forEach(function(item) {
    //                 if (item.querySelector('.rdvPasse')) {
    //                     item.classList.toggle('d-none');
    //                 }
    //             });
    //         }
    //     });
    // });

</script>
</body>
</html>