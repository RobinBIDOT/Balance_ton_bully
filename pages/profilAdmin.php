<?php
/**
 * Ce script PHP sert à préparer l'interface d'administration en récupérant les données nécessaires et en les rendant disponibles pour un traitement ultérieur en JavaScript.
 */
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérification si l'utilisateur est connecté et a le rôle d'admin

if (!isset($_SESSION['nickName']) || $_SESSION['id_role'] != 1) {
    // Redirection si l'utilisateur n'est pas admin ou n'est pas connecté
    header('Location: ../php/index.php'); // Redirige vers la page d'accueil ou de connexion

    exit();
}

// Préparation des données des dons pour JavaScript
$donsData = [];

if (isset($_SESSION['nickName']) && $_SESSION['id_role'] == 1) {

    $stmt = $dbh->prepare("SELECT * FROM Dons WHERE est_paye = TRUE ORDER BY date_paiement DESC");
    $stmt->execute();
    $donsData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Convertit les données PHP en JSON pour JavaScript
$jsonDonsData = json_encode($donsData);

if (isset($_POST['donId'])) {
    $donId = $_POST['donId'];
    try {
        $stmt = $dbh->prepare("UPDATE Dons SET stopper_don_mensuel = TRUE, date_arret_don_mensuel = CURDATE() WHERE id = ?");
        $stmt->execute([$donId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}
//else {
//    echo json_encode(['success' => false, 'error' => 'No donId provided']);
//}

// Préparation des données des actualités pour JavaScript
$actualitesData = [];

if (isset($_SESSION['nickName']) && $_SESSION['id_role'] == 1) {

    $stmt = $dbh->prepare("SELECT * FROM actualites ORDER BY date_publication DESC");
    $stmt->execute();
    $actualitesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Convertit les données PHP en JSON pour JavaScript
$jsonActualitesData = json_encode($actualitesData);

// Préparation des données des utilisateurs pour JavaScript
$utilisateursData = [];

if (isset($_SESSION['nickName']) && $_SESSION['id_role'] == 1) {

    $stmt = $dbh->prepare("SELECT utilisateurs.*, roles.role FROM utilisateurs JOIN roles ON utilisateurs.id_role = roles.id");
    $stmt->execute();
    $utilisateursData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Convertit les données PHP en JSON pour JavaScript
$jsonUtilisateursData = json_encode($utilisateursData);

// Récupération des données des réponses
$reponsesData = [];
$stmt = $dbh->prepare("SELECT * FROM reponses_forum");
$stmt->execute();
$reponsesData = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Convertit les données PHP en JSON pour JavaScript
$jsonReponsesData = json_encode($reponsesData);

// Pour l'affichage de la liste des professionnels de santé
$query = "
    SELECT ps.id, ps.nom, ps.prenom, ps.profession, ps.adresse, ps.ville, ps.code_postal, ps.presentation,
           GROUP_CONCAT(DISTINCT e.nom ORDER BY e.nom SEPARATOR '\n') AS expertises,
           GROUP_CONCAT(CONCAT(IFNULL(hp.jour_semaine, 'Fermé'), ': ', 
                                IFNULL(CONCAT(hp.heure_debut_matin, '-', hp.heure_fin_matin), 'Fermé'), ' / ', 
                                IFNULL(CONCAT(hp.heure_debut_apres_midi, '-', hp.heure_fin_apres_midi), 'Fermé')) 
                        ORDER BY hp.jour_semaine SEPARATOR '\n') AS horaires
    FROM professionnels_sante ps
    LEFT JOIN professionnel_expertise pe ON ps.id = pe.professionnel_id
    LEFT JOIN expertise e ON pe.expertise_id = e.id
    LEFT JOIN horaires_professionnels hp ON ps.id = hp.professionnel_id
    GROUP BY ps.id, ps.nom, ps.prenom, ps.profession, ps.adresse, ps.ville, ps.code_postal, ps.presentation
    ORDER BY ps.nom, ps.prenom";

$stmt = $dbh->prepare($query);
$stmt->execute();
$prosData = $stmt->fetchAll(PDO::FETCH_ASSOC);
$jsonProsData = json_encode($prosData);

// Pour l'affichage des horaires des professionnels de santé
$queryHoraires = "
    SELECT professionnel_id,
           jour_semaine,
           CASE
               WHEN heure_debut_matin IS NULL AND heure_fin_matin IS NULL AND heure_debut_apres_midi IS NULL AND heure_fin_apres_midi IS NULL THEN 'Fermé'
               WHEN heure_debut_matin IS NULL AND heure_fin_matin IS NULL THEN CONCAT('Fermé (après-midi : ', heure_debut_apres_midi, ' - ', heure_fin_apres_midi, ')')
               WHEN heure_debut_apres_midi IS NULL AND heure_fin_apres_midi IS NULL THEN CONCAT('Fermé (matin : ', heure_debut_matin, ' - ', heure_fin_matin, ')')
               ELSE CONCAT('Matin : ', heure_debut_matin, ' - ', heure_fin_matin, ', Après-midi : ', heure_debut_apres_midi, ' - ', heure_fin_apres_midi)
           END AS horaires
    FROM horaires_professionnels
    ORDER BY professionnel_id, FIELD(jour_semaine, 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche')";

$stmtHoraires = $dbh->prepare($queryHoraires);
$stmtHoraires->execute();
$horairesData = $stmtHoraires->fetchAll(PDO::FETCH_ASSOC);
$jsonHorairesData = json_encode($horairesData);

// Récupérer les messages de contact depuis la base de données
$stmtMessagesContact = $dbh->prepare("SELECT id, nom, prenom, mail, telephone, message, date, est_traite FROM messages_contact ORDER BY date DESC");
$stmtMessagesContact->execute();
$messagesContactData = $stmtMessagesContact->fetchAll(PDO::FETCH_ASSOC);
$jsonMessagesContactData = json_encode($messagesContactData);

// Récupérer les demandes de formation depuis la base de données
$stmtDemandesFormation = $dbh->prepare("SELECT id, nom, prenom, mail, telephone, message, date, est_traite FROM demandes_formation ORDER BY date DESC");
$stmtDemandesFormation->execute();
$demandesFormationData = $stmtDemandesFormation->fetchAll(PDO::FETCH_ASSOC);
$jsonDemandesFormationData = json_encode($demandesFormationData);

// Récupérer les demandes d'intervention depuis la base de données
$stmtDemandesIntervention = $dbh->prepare("SELECT id, nom_etablissement, numero_siret, nom_referent_projet, prenom_referent_projet, mail, telephone, date, date_souhaite_intervention, est_traite FROM demandes_intervention ORDER BY date DESC");
$stmtDemandesIntervention->execute();
$demandesInterventionData = $stmtDemandesIntervention->fetchAll(PDO::FETCH_ASSOC);
$jsonDemandesInterventionData = json_encode($demandesInterventionData);

if(isset($_GET['updateStatus'])) {
    $messageId = $_GET['messageId'];
    $newStatus = $_GET['newStatus'];
    $type = $_GET['type'];

    var_dump($messageId, $newStatus, $type);

    switch ($type) {
        case 'messages_contact':
            $updateQuery = "UPDATE messages_contact SET est_traite = :newStatus WHERE id = :id";
            break;
        case 'demandes_formation':
            $updateQuery = "UPDATE demandes_formation SET est_traite = :newStatus WHERE id = :id";
            break;
        case 'demandes_intervention':
            $updateQuery = "UPDATE demandes_intervention SET est_traite = :newStatus WHERE id = :id";
            break;
        default:
            break;
    }

    if (isset($updateQuery)) {
        $stmt = $dbh->prepare($updateQuery);
        $stmt->bindParam(':newStatus', $newStatus, PDO::PARAM_BOOL);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    // Après la mise à jour, renvoyer les données mises à jour pour le type spécifique
    switch ($type) {
        case 'messages_contact':
            // Récupérer les messages de contact mis à jour et les renvoyer
            $stmtUpdatedMessagesContact = $dbh->prepare("SELECT id, nom, prenom, mail, telephone, message, date, est_traite FROM messages_contact ORDER BY date DESC");
            $stmtUpdatedMessagesContact->execute();
            $updatedMessagesContactData = $stmtUpdatedMessagesContact->fetchAll(PDO::FETCH_ASSOC);
            $jsonUpdatedMessagesContactData = json_encode($updatedMessagesContactData);
            break;
        case 'demandes_formation':
            // Récupérer les demandes de formation mises à jour et les renvoyer
            $stmtUpdatedDemandesFormation = $dbh->prepare("SELECT id, nom, prenom, mail, telephone, message, date, est_traite FROM demandes_formation ORDER BY date DESC");
            $stmtUpdatedDemandesFormation->execute();
            $updatedDemandesFormationData = $stmtUpdatedDemandesFormation->fetchAll(PDO::FETCH_ASSOC);
            $jsonUpdatedDemandesFormationData = json_encode($updatedDemandesFormationData);
            break;
        case 'demandes_intervention':
            // Récupérer les demandes d'intervention mises à jour et les renvoyer
            $stmtUpdatedDemandesIntervention = $dbh->prepare("SELECT id, nom_etablissement, numero_siret, nom_referent_projet, prenom_referent_projet, mail, telephone, date, date_souhaite_intervention, est_traite FROM demandes_intervention ORDER BY date DESC");
            $stmtUpdatedDemandesIntervention->execute();
            $updatedDemandesInterventionData = $stmtUpdatedDemandesIntervention->fetchAll(PDO::FETCH_ASSOC);
            $jsonUpdatedDemandesInterventionData = json_encode($updatedDemandesInterventionData);
            break;
        default:
            break;
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit;
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page administrateur</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleProfilAdmin.css">
    <link rel="stylesheet" href="../css/styleClock.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container-fluid mt-5 ">
    <h1 class="text-center mb-4">Administration</h1>
    <div class="row justify-content-center my-5">
        <div class="col-6 col-md-8 d-flex flex-wrap justify-content-around mb-5">
            <button onclick="loadContent('actualites')" class="btn btn-primary admin-btn"><i class="fa-solid fa-newspaper"></i> Gérer les actualités</button>
            <button onclick="loadContent('dons')" class="btn btn-primary admin-btn"><i class="fa-solid fa-credit-card"></i> Gérer les dons</button>
            <button onclick="loadContent('utilisateurs')" class="btn btn-primary admin-btn"><i class="fa-regular fa-user"></i> Gérer les utilisateurs</button>
            <button onclick="loadContent('signalements')" class="btn btn-primary admin-btn"><i class="fa-solid fa-circle-exclamation"></i> Voir les signalements</button>
            <button onclick="loadContent('pro_sante')" class="btn btn-primary admin-btn"><i class="fa-solid fa-notes-medical"></i> Gérer les professionnels de santé</button>
            <button onclick="loadContent('messages_contact')" class="btn btn-primary admin-btn"><i class="fa-solid fa-address-book"></i> Messages de contact</button>
            <button onclick="loadContent('demandes_formation')" class="btn btn-primary admin-btn"><i class="fa-brands fa-leanpub"></i>Demandes de formation</button>
            <button onclick="loadContent('demandes_intervention')" class="btn btn-primary admin-btn"><i class="fa-solid fa-school"></i> Demandes d'intervention</button>
        </div>
    </div>
    <div class="container-fluid mb-4">
        <div class="row justify-content-center">
            <div class="col-6 col-md-6">
                <div class="wrapper">
                    <main></main>
                </div>
            </div>
        </div>
    </div>
    <div class="content-area">
        <!-- Le contenu sera chargé ici -->
    </div>
</div>
<!-- Modale pour la modification d'une actualité -->
<div id="modalEditActu" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'actualité</h5>
            </div>
            <form id="formEditActu" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Champs du formulaire -->
                    <input type="hidden" id="editActuId" name="id">
                    <div class="form-group">
                        <label for="editActuTitre">Titre</label>
                        <input type="text" class="form-control" id="editActuTitre" name="titre" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editActuContenu">Contenu</label>
                        <textarea class="form-control" id="editActuContenu" name="contenu" autocomplete="off"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="editActuLien">Lien de l'article</label>
                        <input type="text" class="form-control" id="editActuLien" name="lien_article" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="photoChanged" value="yes"> Cochez si vous changez la photo
                        <label for="editActuPhoto">Photo</label>
                        <input type="file" class="form-control" id="editActuPhoto" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="annulerEditActu">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modale pour la modification d'un utilisateur -->
<div id="modalEditUser" class="modal" tabindex="-2">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'utilisateur</h5>
            </div>
            <form id="formEditUser" enctype="multipart/form-data">
                <div class="modal-body">
                    <!-- Champs du formulaire -->
                    <input type="hidden" id="editUserId" name="id">
                    <div class="form-group">
                        <label for="editUserFirstName">Prénom</label>
                        <input type="text" class="form-control" id="editUserFirstName" name="firstName" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editUserName">Nom</label>
                        <input type="text" class="form-control" id="editUserName" name="name" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editUserUserName">Pseudo</label>
                        <input type="text" class="form-control" id="editUserUserName" name="userName" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editUserMail">Email</label>
                        <input type="email" class="form-control" id="editUserMail" name="mail" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="editUserPhoto">Photo Avatar</label>
                        <input type="file" class="form-control" id="editUserPhoto" name="photo_avatar">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="annulerEditUser">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- Modale pour la modification d'une réponse signalée par un utilisateur -->
<div id="modalEditReponse" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier la réponse</h5>
            </div>
            <form id="formEditReponse">
                <div class="modal-body">
                    <input type="hidden" id="editReponseId" name="id">
                    <div class="form-group">
                        <label for="editReponseContenu">Contenu de la réponse</label>
                        <textarea class="form-control" id="editReponseContenu" name="contenu" required autocomplete="off"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal" id="annulerEditAnswer">Annuler</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
<script src="../js/scriptClock.js"></script>
<script>
    // Rend les données PHP disponibles en tant qu'objet JavaScript
    var donsData = <?php echo $jsonDonsData; ?>;
    var actualitesData = <?php echo $jsonActualitesData; ?>;
    var utilisateursData = <?php echo $jsonUtilisateursData; ?>;
    var reponsesData = <?php echo $jsonReponsesData; ?>;
    var prosData = <?php echo $jsonProsData; ?>;
    var messagesContactData = <?php echo $jsonMessagesContactData; ?>;
    var demandesFormationData = <?php echo $jsonDemandesFormationData; ?>;
    var demandesInterventionData = <?php echo $jsonDemandesInterventionData; ?>;
    var updatedMessagesContactData = <?php echo isset($jsonUpdatedMessagesContactData) ? $jsonUpdatedMessagesContactData : '[]'; ?>;
    var updatedDemandesFormationData = <?php echo isset($jsonUpdatedDemandesFormationData) ? $jsonUpdatedDemandesFormationData : '[]'; ?>;
    var updatedDemandesInterventionData = <?php echo isset($jsonUpdatedDemandesInterventionData) ? $jsonUpdatedDemandesInterventionData : '[]'; ?>;


    /**
     * Charge le contenu spécifique en fonction du type sélectionné.
     * @param {string} type - Le type de contenu à charger ('actualites', 'dons', 'utilisateurs', 'signalements', 'pro_sante').
     */
    function loadContent(type) {
        var contentArea = document.querySelector('.content-area');
        const wrapper = document.querySelector('.wrapper');
        switch (type) {
            case 'actualites':
                displayActualites(contentArea);
                wrapper.style.display = 'none';
                break;
            case 'dons':
                displayDons(contentArea);
                wrapper.style.display = 'none';
                break;
            case 'utilisateurs':
                displayUtilisateurs(contentArea);
                wrapper.style.display = 'none';
                break;
            case 'signalements':
                fetch('../php/load_signalements.php')
                    .then(response => response.json())
                    .then(data => displaySignalements(data, document.querySelector('.content-area')))
                    .catch(error => console.error('Erreur:', error));
                wrapper.style.display = 'none';
                break;
            case 'pro_sante':
                displayProSante(contentArea);
                wrapper.style.display = 'none';
                break;
            case 'messages_contact':
                displayMessagesContact(contentArea, messagesContactData);
                wrapper.style.display = 'none';
                break;
            case 'demandes_formation':
                displayDemandesFormation(contentArea, demandesFormationData);
                wrapper.style.display = 'none';
                break;
            case 'demandes_intervention':
                displayDemandesIntervention(contentArea, demandesInterventionData);
                wrapper.style.display = 'none';
                break;
            default:
                contentArea.innerHTML = '<p class="text-center mb-4">Choisissez une option pour commencer.</p>';
                wrapper.style.display = 'block';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadContent(null);
    });

    /**
     * Affiche les informations sur les actualités dans la zone de contenu.
     *
     * Cette fonction génère et affiche un tableau HTML contenant la liste des actualités.
     * Chaque ligne du tableau inclut des données telles que le titre de l'actualité, une image,
     * le contenu de l'actualité, un lien vers l'article complet, la date de publication,
     * ainsi que des boutons pour modifier et supprimer l'actualité.
     *
     * @param {HTMLElement} contentArea - L'élément dans lequel afficher les données des actualités.
     */
    function displayActualites(contentArea) {
        var tableHtml = '<h2 class="text-center mb-4">Gestion des actualités</h2>';
        tableHtml += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        tableHtml += '<th>Titre</th><th>Photo</th><th>Contenu</th><th>Lien article</th><th>Date de publication</th><th>Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        actualitesData.forEach(function(actu) {
            tableHtml += '<tr>';
            tableHtml += "<td>" + actu.titre + "</td>";
            tableHtml += "<td><img src='" + actu.photo + "' alt='Photo' style='width: 100px;'></td>";
            tableHtml += "<td>" + actu.contenu + "</td>";
            tableHtml += "<td><a href='" + actu.lien_article + "'>Lien</a></td>";
            tableHtml += "<td>" + actu.date_publication + "</td>";
            tableHtml += "<td>";
            tableHtml += "<button class='btn btn-primary'  onclick='editActu(" + actu.id_actualite + ")'>Modifier</button> ";
            tableHtml += "<button class='btn btn-danger'  onclick='deleteActu(" + actu.id_actualite + ")'>Supprimer</button>";
            tableHtml += "</td>";
            tableHtml += '</tr>';
        });

        tableHtml += '</tbody></table></div>';
        contentArea.innerHTML = tableHtml;
    }

    /**
     * Supprime une actualité spécifique de la base de données.
     *
     * Cette fonction envoie une requête POST à un script PHP pour supprimer l'actualité avec l'ID donné.
     * Si la requête réussit, la liste des actualités est rechargée pour refléter les changements.
     *
     * @param {number} id - L'identifiant de l'actualité à supprimer.
     */
    function deleteActu(id) {
        if(confirm('Voulez-vous vraiment supprimer cette actualité ?')) {
            let formData = new FormData();
            formData.append('id', id);

            fetch('../php/delete_actualite.php', {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Actualité supprimée avec succès.');
                        window.location.reload();
                    } else {
                        alert('Erreur lors de la suppression.');
                    }
                })
                .catch(error => console.error('Erreur:', error));
        }
    }

    /**
     * Affiche le formulaire de modification pour une actualité spécifique.
     *
     * Cette fonction ouvre un modal contenant le formulaire de modification.
     * Elle doit également remplir les champs du formulaire avec les données de l'actualité.
     *
     * @param {number} id - L'identifiant de l'actualité à modifier.
     */
    function editActu(id) {
        // Récupérez les données de l'actualité et préremplissez le formulaire
        $('#modalEditActu').modal('show');
        // Trouver les données de l'actualité à partir de son ID
        const actu = actualitesData.find(actu => actu.id_actualite === id);
        if (actu) {
            // Préremplir le formulaire avec les données de l'actualité
            document.getElementById('editActuId').value = actu.id_actualite;
            document.getElementById('editActuTitre').value = actu.titre;
            document.getElementById('editActuContenu').value = actu.contenu;
            document.getElementById('editActuLien').value = actu.lien_article;

            // Afficher le modal
            $('#modalEditActu').modal('show');
        } else {
            alert("Actualité introuvable.");
        }
    }

    document.getElementById('annulerEditActu').addEventListener('click', function() {
        $('#modalEditActu').modal('hide');
    });

    // Gérer la soumission du formulaire de modification
    document.getElementById('formEditActu').addEventListener('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);

        fetch('update_actualite.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Actualité mise à jour avec succès.');
                    $('#modalEditActu').modal('hide');
                    window.location.reload();
                } else {
                    alert('Erreur lors de la mise à jour : ' + data.error);
                }
            })
            .catch(error => console.error('Erreur:', error));
    });


    /**
     * Affiche les informations sur les dons dans la zone de contenu.
     * @param {HTMLElement} contentArea - L'élément dans lequel afficher les données.
     */
    function displayDons(contentArea) {
        var tableHtml = '<h2 class="text-center mb-4">Visualiser les dons. Arrêter les paiements récurrents.</h2>';
        tableHtml += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        tableHtml += '<th>Type de don</th><th>Montant</th><th>Prénom</th><th>Nom</th><th>Email</th>';
        tableHtml += '<th>Date de naissance</th><th>Adresse</th><th>Code Postal</th><th>Ville</th><th>Pays</th>';
        tableHtml += '<th>Raison sociale</th><th>SIREN</th><th>Forme juridique</th><th>Date de paiement</th><th>Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        donsData.forEach(function(don) {
            var montantAffiche = don.montant ? don.montant : don.montant_libre;
            tableHtml += '<tr>';
            tableHtml += "<td>" + don.type_don + "</td>";
            tableHtml += "<td>" + montantAffiche + "</td>";
            tableHtml += "<td>" + don.prenom + "</td>";
            tableHtml += "<td>" + don.nom + "</td>";
            tableHtml += "<td>" + don.email + "</td>";
            tableHtml += "<td>" + don.date_naissance + "</td>";
            tableHtml += "<td>" + don.adresse + "</td>";
            tableHtml += "<td>" + don.code_postal + "</td>";
            tableHtml += "<td>" + don.ville + "</td>";
            tableHtml += "<td>" + don.pays + "</td>";
            tableHtml += "<td>" + (don.est_organisme ? don.raison_sociale : "") + "</td>";
            tableHtml += "<td>" + (don.est_organisme ? don.siren : "") + "</td>";
            tableHtml += "<td>" + (don.est_organisme ? don.forme_juridique : "") + "</td>";
            tableHtml += "<td>" + don.date_paiement + "</td>";

            if (don.type_don === 'Don mensuel') {
                if (don.stopper_don_mensuel) {
                    tableHtml += "<td>Mensualités arrêtées le : " + don.date_arret_don_mensuel + "</td>";
                } else {
                    tableHtml += "<td><button class='btn btn-danger' onclick='stopDonMensuel(" + don.id + ")'>Arrêter les mensualités</button></td>";
                }
            } else {
                tableHtml += "<td></td>";
            }
            tableHtml += '</tr>';
        });

        tableHtml += '</tbody></table>';
        contentArea.innerHTML = tableHtml;
    }

    /**
     * Arrête les mensualités d'un don mensuel et met à jour les informations dans la base de données.
     *
     * Cette fonction envoie une requête POST à un script PHP sur le serveur, en passant l'identifiant du don.
     * Si la requête réussit, les mensualités du don spécifié sont arrêtées, et la page est rechargée pour afficher les changements.
     * En cas d'erreur, une alerte est affichée avec le message d'erreur.
     *
     * @param {number} donId - L'identifiant du don dont les mensualités doivent être arrêtées.
     */
    function stopDonMensuel(donId) {
        let formData = new FormData();
        formData.append('donId', donId);

        fetch('../php/stop_don_mensuel.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if(data.success) {
                    alert('Les mensualités ont été arrêtées.');
                    // Rechargement de la page pour refléter les changements
                    window.location.reload();
                } else {
                    alert('Erreur lors de l\'arrêt des mensualités : ' + data.error);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
            });
    }

    /**
     * Affiche les informations sur les utilisateurs dans la zone de contenu.
     *
     * Cette fonction génère et affiche un tableau HTML contenant la liste des utilisateurs.
     * Chaque ligne du tableau inclut des informations telles que le prénom, le nom, le pseudo,
     * l'adresse e-mail, la photo de l'avatar, le rôle, ainsi que des boutons pour modifier et supprimer l'utilisateur.
     *
     * @param {HTMLElement} contentArea - L'élément dans lequel afficher les données des utilisateurs.
     */
    function displayUtilisateurs(contentArea) {
        var tableHtml = '<h2 class="text-center mb-4">Gestion des utilisateurs</h2>';
        tableHtml += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        tableHtml += '<th>Prénom</th><th>Nom</th><th>Pseudo</th><th>Mail</th><th>Photo Avatar</th><th>Rôle</th><th class=text-center pe-4>Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        utilisateursData.forEach(function(user) {
            tableHtml += '<tr>';
            tableHtml += "<td>" + user.firstName + "</td>";
            tableHtml += "<td>" + user.name + "</td>";
            tableHtml += "<td>" + user.userName + "</td>";
            tableHtml += "<td>" + user.mail + "</td>";
            tableHtml += "<td><img src='" + user.photo_avatar + "' alt='Avatar' class=img-fluid style='width: 50px; max-width: 60px; '></td>";
            tableHtml += "<td>" + user.role + "</td>";
            tableHtml += "<td class=text-center pe-4>";
            tableHtml += "<button class='btn btn-primary me-2' onclick='editUser(" + user.id + ")'>Modifier</button> ";
            tableHtml += "<button class='btn btn-danger' onclick='deleteUser(" + user.id + ")'>Supprimer</button>";
            tableHtml += "</td>";
            tableHtml += '</tr>';
        });
        tableHtml += '</tbody></table></div>';
        contentArea.innerHTML = tableHtml;
    }


    /**
     * Ouvre une modale de modification pour un utilisateur spécifique.
     * Récupère les données existantes de l'utilisateur et les charge dans les champs du formulaire de la modale.
     * @param {number} id - L'identifiant de l'utilisateur à modifier.
     */
    function editUser(id) {
        // Trouver les données de l'utilisateur à partir de son ID
        const user = utilisateursData.find(user => user.id === id);
        console.log(user);
        if (user) {
            // Préremplir le formulaire avec les données de l'utilisateur
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserFirstName').value = user.firstName;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserUserName').value = user.userName;
            document.getElementById('editUserMail').value = user.mail;

            // Effectuer une requête AJAX pour récupérer d'autres données de l'utilisateur si nécessaire
            $.ajax({
                url: "../php/update_user.php",
                type: "POST",
                data: { id: user.id },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                },
                error: function(xhr, status, error) {
                    console.error("Erreur lors de la requête AJAX:", error);
                }
            });

            // Afficher le modal
            $('#modalEditUser').modal('show');
        } else {
            alert("Utilisateur introuvable.");
        }
    }


    document.getElementById('annulerEditUser').addEventListener('click', function() {
        $('#modalEditUser').modal('hide');
    });

    /**
     * Supprime un utilisateur de la base de données après confirmation.
     * Envoie une requête POST au serveur pour effectuer la suppression.
     * @param {number} id - L'identifiant de l'utilisateur à supprimer.
     */
    async function deleteUser(id) {
        if(confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
            console.log(id)
            let formData = new FormData();
            formData.append('id', id);
            console.log(formData)
            const response = await fetch("../php/delete_user.php", {
                method: 'POST',
                body: formData
            })
            let json = await response.json();
            if (json.status === 'success'){
                console.log(json.message)
                alert(json.message)
            }
            console.log(json);
        }
    }

    /**
     * Affiche les signalements dans la zone de contenu spécifiée.
     *
     * Cette fonction génère et affiche un tableau HTML contenant la liste des signalements.
     * Chaque ligne du tableau inclut des informations sur le sujet signalé, l'auteur de la réponse,
     * la date de la réponse, le contenu de la réponse, la date du signalement, ainsi que des boutons
     * pour modifier ou supprimer la réponse signalée.
     *
     * @param {Array} data - Les données des signalements à afficher. Chaque élément de l'array doit contenir
     *                       les propriétés : titreSujet, nomAuteurSujet, dateCreationSujet, nomAuteurReponse,
     *                       dateReponse, contenuReponse, dateSignalement, et idReponse.
     * @param {HTMLElement} contentArea - L'élément HTML où le tableau des signalements sera injecté.
     */
    function displaySignalements(data, contentArea) {
        let tableHtml = '<h2 class="text-center mb-4">Gestion des Signalements</h2>';
        tableHtml += '<table class="table table-striped"><thead><tr>';
        tableHtml += '<th>Titre du sujet</th><th>Auteur du sujet</th><th>Date création sujet</th>';
        tableHtml += '<th>Auteur réponse</th><th>Date réponse</th><th>Contenu réponse</th>';
        tableHtml += '<th>Date signalement</th><th>Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        data.forEach(signalement => {
            tableHtml += `<tr>
            <td>${signalement.titreSujet}</td>
            <td>${signalement.nomAuteurSujet}</td>
            <td>${signalement.dateCreationSujet}</td>
            <td>${signalement.nomAuteurReponse}</td>
            <td>${signalement.dateReponse}</td>
            <td>${signalement.contenuReponse}</td>
            <td>${signalement.dateSignalement}</td>
            <td class='align-middle'>
                <button class='btn btn-primary mb-1'  onclick='editReponse(${signalement.idReponse})'>Modifier</button>
                <button class='btn btn-danger mb-1'  onclick='deleteReponse(${signalement.idReponse})'>Supprimer</button>
            </td>
        </tr>`;
        });

        tableHtml += '</tbody></table>';
        contentArea.innerHTML = tableHtml;
    }

    /**
     * Ouvre une modale de modification pour une réponse spécifique.
     * Récupère les données existantes de la réponse et les charge dans les champs du formulaire de la modale.
     * @param {number} idReponse - L'identifiant de la réponse à modifier.
     */
    function editReponse(idReponse) {
        // Ici, vous devez obtenir les données de la réponse par une requête AJAX ou en les stockant en JavaScript
        // Pour l'exemple, les données sont saisies directement
        fetch(`../php/get_reponse_data.php?id=${idReponse}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editReponseId').value = idReponse;
                document.getElementById('editReponseContenu').value = data.contenu;
                $('#modalEditReponse').modal('show');
            })
            .catch(error => console.error('Erreur:', error));
    }

    document.getElementById('annulerEditAnswer').addEventListener('click', function() {
        $('#modalEditReponse').modal('hide');
    });

    /**
     * Supprime une réponse de la base de données après confirmation.
     * Envoie une requête POST au serveur pour effectuer la suppression.
     * @param {number} idReponse - L'identifiant de la réponse à supprimer.
     */
    function deleteReponse(idReponse) {
        if(confirm('Voulez-vous vraiment supprimer cette réponse ?')) {
            console.log("Envoi de la requête avec l'ID :", JSON.stringify({ id: idReponse }));
            fetch('../php/delete_reponse.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: idReponse })
            })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Réponse du serveur non OK');
                    }
                    return response.json();
                })
                .then(data => {
                    if(data.success) {
                        alert('Réponse supprimée avec succès.');
                        loadContent('signalements');
                    } else {
                        alert('Erreur lors de la suppression : ' + data.error);
                    }
                })
                // .catch(error => console.error('Erreur:', error));
        }
    }

    // Gérer la soumission du formulaire de modification
    document.getElementById('formEditReponse').addEventListener('submit', function(e) {
        e.preventDefault();

        let formData = new FormData(this);
        formData.append('id', document.getElementById('editReponseId').value);
        formData.append('contenu', document.getElementById('editReponseContenu').value);

        fetch('../php/update_reponse.php', {
            method: 'POST',
            body: formData
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Réponse mise à jour avec succès.');
                    $('#modalEditReponse').modal('hide');
                    loadContent('signalements');
                } else {
                    alert('Erreur lors de la mise à jour.');
                }
            })
            .catch(error => console.error('Erreur:', error));
    });


    /**
     * Affiche les informations sur les professionnels de santé dans la zone de contenu spécifiée.
     *
     * Cette fonction génère et affiche un tableau HTML contenant la liste des professionnels de santé.
     * Chaque ligne du tableau inclut des informations telles que le nom, la profession, l'adresse,
     * la ville, le code postal, la présentation, l'expertise, les horaires, ainsi que des boutons
     * pour modifier et supprimer le professionnel de santé.
     *
     * @param {HTMLElement} contentArea - L'élément dans lequel afficher les données des professionnels de santé.
     */
    function displayProSante(contentArea) {
        var tableHtml = '<h2 class="text-center mb-4">Gestion des professionnels de santé</h2>';
        tableHtml += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        tableHtml += '<th class="align-middle">Nom et Prénom</th><th class="align-middle">Profession</th><th class="align-middle">Adresse - Ville - Code Postal</th><th class="align-middle">Présentation</th><th class="align-middle">Expertise</th><th class="align-middle">Horaires</th><th class="align-middle">Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        prosData.forEach(function(pro) {
            tableHtml += '<tr>';
            tableHtml += `<td class="align-middle">${pro.nom} ${pro.prenom}</td>`;
            tableHtml += `<td class="align-middle">${pro.profession}</td>`;
            tableHtml += `<td class="align-middle">${pro.adresse}, ${pro.ville}, ${pro.code_postal}</td>`;
            tableHtml += `<td class="align-middle">${pro.presentation}</td>`;

            // Conversion des chaînes en tableaux et affichage des expertises
            var expertisesArray = pro.expertises ? pro.expertises.split('\n') : [];
            var expertisesHtml = expertisesArray.join('<br>');
            tableHtml += `<td class="align-middle">${expertisesHtml}</td>`;

            // Récupération et affichage des horaires sans doublons
            var horairesArray = pro.horaires ? pro.horaires.split('\n') : [];
            var horairesSet = new Set(horairesArray);
            var horairesHtml = Array.from(horairesSet).join('<br>');
            tableHtml += `<td class="align-middle">${horairesHtml}</td>`;

            tableHtml += "<td class='align-middle'><button class='btn btn-primary w-75 my-1'>Modifier</button> <button class='btn btn-danger w-75 my-1'>Supprimer</button></td>";
            tableHtml += '</tr>';
        });

        tableHtml += '</tbody></table></div>';
        contentArea.innerHTML = tableHtml;
    }

    /**
     * Formate et retourne une chaîne de caractères représentant les horaires d'un professionnel de santé.
     *
     * Cette fonction prend en entrée un objet représentant un professionnel de santé et
     * retourne une chaîne formatée indiquant ses horaires. Les horaires incluent les heures de début
     * et de fin le matin et l'après-midi pour chaque jour de la semaine.
     *
     * @param {Object} pro - L'objet représentant un professionnel de santé.
     * @returns {string} Une chaîne de caractères représentant les horaires du professionnel.
     */
    function formatHoraires(pro) {
        return pro.jour_semaine + ' ' + pro.heure_debut_matin + ' - ' + pro.heure_fin_matin + ' / ' + pro.heure_debut_apres_midi + ' - ' + pro.heure_fin_apres_midi;
    }

    /**
     * Fonction pour afficher les messages de contact.
     * @param {HTMLElement} contentArea - La zone où afficher les messages de contact.
     * @param {array} messages - Les messages à afficher.
     */
    function displayMessagesContact(contentArea, messages) {
        let html = '<h2 class="text-center mb-4">Messages de contact</h2>';
        html += `
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-primary m-2 filter-button" data-status="messages_contact">Tout afficher</button>
                    <button class="btn btn-success m-2 filter-button" data-status="done">Terminés</button>
                    <button class="btn btn-warning m-2 filter-button" data-status="todo">À faire</button>
                </div>
            </div>
        </div>
        <div class="no-items-message text-center mt-3" style="display: none;">Aucun message traité.</div>
        `;
        html += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        html += '<th>Nom</th><th>Email</th><th>Téléphone</th><th>Message</th><th>Date</th><th>Suivi</th>';
        html += '</tr></thead><tbody>';

        messages.forEach(message => {
            html += '<tr>';
            html += '<td>' + message.nom + ' ' + message.prenom + '</td>';
            html += '<td><a href="mailto:' + message.mail + '">' + message.mail + '</a></td>';
            html += '<td>' + message.telephone + '</td>';
            html += '<td>' + message.message + '</td>';
            html += '<td>' + message.date + '</td>';
            let btnClass = message.est_traite ? 'btn btn-danger' : 'btn btn-success';
            let btnText = message.est_traite ? 'Passer à À faire' : 'Passer à Terminé';
            let newStatus = !message.est_traite;
            html += '<td>' + (message.est_traite ? 'Terminée' : 'À faire') +
                ` <button class="${btnClass} update-button" data-message-id="${message.id}" data-new-status="${newStatus}" data-type="messages_contact">${btnText}</button></td>`;
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        contentArea.innerHTML = html;
    }

    /**
     * Fonction pour afficher les demandes de formation.
     * @param {HTMLElement} contentArea - La zone où afficher les demandes de formation.
     * @param {array} demandes - Les demandes de formation à afficher.
     */
    function displayDemandesFormation(contentArea, demandes) {
        let html = '<h2 class="text-center mb-4">Demandes de formation</h2>';
        html += `
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-primary m-2 filter-button" data-status="demandes_formation">Tout afficher</button>
                    <button class="btn btn-success m-2 filter-button" data-status="done">Terminées</button>
                    <button class="btn btn-warning m-2 filter-button" data-status="todo">À faire</button>
                </div>
            </div>
        </div>
        <div class="no-items-message text-center mt-3" style="display: none;">Aucune demande de formation traitée.</div>
        `;
        html += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        html += '<th>Nom</th><th>Email</th><th>Téléphone</th><th>Message</th><th>Date</th><th>Suivi</th>';
        html += '</tr></thead><tbody>';

        demandes.forEach(demande => {
            html += '<tr>';
            html += '<td>' + demande.nom + ' ' + demande.prenom + '</td>';
            html += '<td><a href="mailto:' + demande.mail + '">' + demande.mail + '</a></td>';
            html += '<td>' + demande.telephone + '</td>';
            html += '<td>' + demande.message + '</td>';
            html += '<td>' + demande.date + '</td>';
            let btnClass = demande.est_traite ? 'btn btn-danger' : 'btn btn-success';
            let btnText = demande.est_traite ? 'Passer à À faire' : 'Passer à Terminé';
            let newStatus = !demande.est_traite;
            html += '<td>' + (demande.est_traite ? 'Terminée' : 'À faire') +
                ` <button class="${btnClass} update-button" data-message-id="${demande.id}" data-new-status="${newStatus}" data-type="demandes_formation">${btnText}</button></td>`;
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        contentArea.innerHTML = html;
    }


    /**
     * Fonction pour afficher les demandes d'intervention.
     * @param {HTMLElement} contentArea - La zone où afficher les demandes d'intervention.
     * @param {array} demandes - Les demandes d'intervention à afficher.
     */
    function displayDemandesIntervention(contentArea, demandes) {
        let html = '<h2 class="text-center mb-4">Demandes d\'intervention</h2>';
        html += `
        <div class="container">
            <div class="row">
                <div class="col text-center">
                    <button class="btn btn-primary m-2 filter-button" data-status="demandes_intervention">Tout afficher</button>
                    <button class="btn btn-success m-2 filter-button" data-status="done">Terminées</button>
                    <button class="btn btn-warning m-2 filter-button" data-status="todo">À faire</button>
                </div>
            </div>
        </div>
        <div class="no-items-message text-center mt-3" style="display: none;">Aucune demande d'intervention traitée.</div>
        `;
        html += '<div class="table-responsive"><table class="table table-striped mt-4"><thead><tr>';
        html += '<th>Établissement</th><th>SIRET</th><th>Référent</th><th>Email</th><th>Téléphone</th><th>Date</th><th>Date d\'intervention souhaitée</th><th>Suivi</th>';
        html += '</tr></thead><tbody>';

        demandes.forEach(demande => {
            html += '<tr>';
            html += '<td>' + demande.nom_etablissement + '</td>';
            html += '<td>' + demande.numero_siret + '</td>';
            html += '<td>' + demande.nom_referent_projet + ' ' + demande.prenom_referent_projet + '</td>';
            html += '<td><a href="mailto:' + demande.mail + '">' + demande.mail + '</a></td>';
            html += '<td>' + demande.telephone + '</td>';
            html += '<td>' + demande.date + '</td>';
            html += '<td>' + demande.date_souhaite_intervention + '</td>';
            let btnClass = demande.est_traite ? 'btn btn-danger' : 'btn btn-success';
            let btnText = demande.est_traite ? 'Passer à À faire' : 'Passer à Terminé';
            let newStatus = !demande.est_traite;
            html += '<td>' + (demande.est_traite ? 'Terminée' : 'À faire') +
                ` <button class="${btnClass} update-button" data-message-id="${demande.id}" data-new-status="${newStatus}" data-type="demandes_intervention">${btnText}</button></td>`;
            html += '</tr>';
        });

        html += '</tbody></table></div>';
        contentArea.innerHTML = html;
    }


    /**
     * Récupère et affiche le contenu mis à jour pour la zone spécifiée.
     */
    function updateContent() {
        $.ajax({
            url: '../pages/profilAdmin.php',
            type: 'GET',
            success: function(response) {
                console.log(response);
                displayMessagesContact(document.querySelector('.content-area'), response.messages);
            },
            error: function(error) {
                console.error('Erreur lors de la récupération des messages:', error);
            }
        });
    }


    document.addEventListener('DOMContentLoaded', function() {
        const filterButtons = document.querySelectorAll('.filter-button');
        filterButtons.forEach(button => {
            button.addEventListener('click', function() {
                const status = this.getAttribute('data-status');
                // Appeler la fonction correspondante selon le type de contenu
                switch (status) {
                    case 'messages_contact':
                        displayMessagesContact(document.querySelector('.content-area'), messagesContactData);
                        break;
                    case 'demandes_formation':
                        displayDemandesFormation(document.querySelector('.content-area'), demandesFormationData);
                        break;
                    case 'demandes_intervention':
                        displayDemandesIntervention(document.querySelector('.content-area'), demandesInterventionData);
                        break;
                    default:
                        break;
                }
            });
        });

        const updateButtons = document.querySelectorAll('.update-button');
        updateButtons.forEach(button => {
            button.addEventListener('click', function() {
                const messageId = this.getAttribute('data-message-id');
                const newStatus = this.getAttribute('data-new-status') === 'true';
                const type = this.getAttribute('data-type');
                updateStatus(messageId, newStatus, type);
            });
        });
    });


    /**
     * Met à jour le statut d'un message et rafraîchit le contenu.
     * @param {number} messageId - L'identifiant du message à mettre à jour.
     * @param {boolean} newStatus - Le nouveau statut à appliquer au message.
     * @param {string} type - Le type de données à mettre à jour ('messages_contact', 'demandes_formation', 'demandes_intervention').
     */
    function updateStatus(messageId, newStatus, type) {
        $.ajax({
            url: '../pages/profilAdmin.php',
            type: 'POST',
            data: {
                'updateStatus': true,
                'messageId': messageId,
                'newStatus': newStatus,
                'type': type
            },
            success: function() {
                updateContent(type);
            },
            error: function(error) {
                console.error('Erreur lors de la mise à jour :', error);
            }
        });
    }


    /**
     * Filtre les messages affichés en fonction du statut.
     * @param {string} status - Le statut pour filtrer ('all', 'done', 'todo').
     */
    function filterContent(status) {
        const rows = document.querySelectorAll('.content-area tr');
        let anyDone = false; // Variable pour vérifier si au moins un élément est terminé
        rows.forEach(row => {
            const isDone = row.querySelector('td:nth-last-child(2)').textContent.trim() === 'Terminée';
            console.log('Row status:', isDone ? 'Terminée' : 'Non terminée');
            if (status === 'all' || (status === 'done' && isDone) || (status === 'todo' && !isDone)) {
                row.style.display = '';
                if (isDone) {
                    anyDone = true;
                }
            } else {
                row.style.display = 'none';
            }
        });

        // Si aucun élément n'est terminé et que le statut est 'done', affichez un message
        if (status === 'done' && !anyDone) {
            console.log('Aucun élément n\'est terminé.');
            document.querySelector('.no-items-message').style.display = 'block';
        } else {
            document.querySelector('.no-items-message').style.display = 'none';
        }
    }

</script>
</body>
</html>