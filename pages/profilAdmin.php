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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page administrateur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <style>
        .admin-btn {
            margin: 10px;
            transition: transform 0.3s ease;
        }
        .admin-btn:hover {
            transform: scale(1.05);
        }
    </style>
</head>
<?php include('../includes/headerNav.php') ?>
<body>
<div class="container-fluid mt-5">
    <h1 class="text-center mb-4">Administration</h1>
    <div class="row justify-content-center mb-5">
        <div class="col-md-8 d-flex flex-wrap justify-content-around">
            <button onclick="loadContent('actualites')" class="btn btn-primary admin-btn">Gérer les actualités</button>
            <button onclick="loadContent('dons')" class="btn btn-success admin-btn">Gérer les dons</button>
            <button onclick="loadContent('utilisateurs')" class="btn btn-warning admin-btn">Gérer les utilisateurs</button>
            <button onclick="loadContent('signalements')" class="btn btn-danger admin-btn">Voir les signalements</button>
            <button onclick="loadContent('pro_sante')" class="btn btn-info admin-btn">Gérer les professionnels de santé</button>
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
                        <label>Titre</label>
                        <input type="text" class="form-control" id="editActuTitre" name="titre">
                    </div>
                    <div class="form-group">
                        <label>Contenu</label>
                        <textarea class="form-control" id="editActuContenu" name="contenu"></textarea>
                    </div>
                    <div class="form-group">
                        <label>Lien de l'article</label>
                        <input type="text" class="form-control" id="editActuLien" name="lien_article">
                    </div>
                    <div class="form-group">
                        <input type="checkbox" name="photoChanged" value="yes"> Cochez si vous changez la photo
                        <label>Photo</label>
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
                        <label>Prénom</label>
                        <input type="text" class="form-control" id="editUserFirstName" name="firstName">
                    </div>
                    <div class="form-group">
                        <label>Nom</label>
                        <input type="text" class="form-control" id="editUserName" name="name">
                    </div>
                    <div class="form-group">
                        <label>Pseudo</label>
                        <input type="text" class="form-control" id="editUserUserName" name="userName">
                    </div>
                    <div class="form-group">
                        <label>Email</label>
                        <input type="email" class="form-control" id="editUserMail" name="mail">
                    </div>
                    <div class="form-group">
                        <label>Photo Avatar</label>
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
                        <label>Contenu de la réponse</label>
                        <textarea class="form-control" id="editReponseContenu" name="contenu" required></textarea>
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Rend les données PHP disponibles en tant qu'objet JavaScript
    var donsData = <?php echo $jsonDonsData; ?>;
    var actualitesData = <?php echo $jsonActualitesData; ?>;
    var utilisateursData = <?php echo $jsonUtilisateursData; ?>;
    var reponsesData = <?php echo $jsonReponsesData; ?>;

    // Données des actualités
    var actualitesData = <?php echo $jsonActualitesData; ?>;
    var utilisateursData = <?php echo $jsonUtilisateursData; ?>;


    /**
     * Charge le contenu spécifique en fonction du type sélectionné.
     * @param {string} type - Le type de contenu à charger ('actualites', 'dons', 'utilisateurs', 'signalements', 'pro_sante').
     */
    function loadContent(type) {
        var contentArea = document.querySelector('.content-area');
        switch (type) {
            case 'actualites':
                displayActualites(contentArea);
                break;
            case 'dons':
                displayDons(contentArea);
                break;
            case 'utilisateurs':
                displayUtilisateurs(contentArea);
                break;
            case 'signalements':
                fetch('load_signalements.php')
                    .then(response => response.json())
                    .then(data => displaySignalements(data, document.querySelector('.content-area')))
                    .catch(error => console.error('Erreur:', error));
                break;
            case 'pro_sante':
                contentArea.innerHTML = '<h2 class="text-center mb-4">Gestion des professionnels de santé</h2><p class="text-center mb-4">Ici, vous pouvez gérer les professionnels de santé.</p>';
                break;
            default:
                contentArea.innerHTML = '<p class="text-center mb-4">Choisissez une option pour commencer.</p>';
        }

    }

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
            tableHtml += "<button class='btn btn-outline-primary' onclick='editActu(" + actu.id_actualite + ")'>Modifier</button> ";
            tableHtml += "<button class='btn btn-outline-danger' onclick='deleteActu(" + actu.id_actualite + ")'>Supprimer</button>";
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

            fetch('delete_actualite.php', {
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
        var tableHtml = '<h2 class="text-center mb-4">Ici, vous pouvez visualiser les dons.</h2>';
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
                    tableHtml += "<td><button class='btn btn-outline-danger' onclick='stopDonMensuel(" + don.id + ")'>Arrêter les mensualités</button></td>";
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

        fetch('stop_don_mensuel.php', {
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
        tableHtml += '<th>Prénom</th><th>Nom</th><th>Pseudo</th><th>Mail</th><th>Photo Avatar</th><th>Rôle</th><th>Actions</th>';
        tableHtml += '</tr></thead><tbody>';

        utilisateursData.forEach(function(user) {
            tableHtml += '<tr>';
            tableHtml += "<td>" + user.firstName + "</td>";
            tableHtml += "<td>" + user.name + "</td>";
            tableHtml += "<td>" + user.userName + "</td>";
            tableHtml += "<td>" + user.mail + "</td>";
            tableHtml += "<td><img src='" + user.photo_avatar + "' alt='Avatar' style='width: 50px;'></td>";
            tableHtml += "<td>" + user.role + "</td>";
            tableHtml += "<td>";
            tableHtml += "<button class='btn btn-outline-primary' onclick='editUser(" + user.id + ")'>Modifier</button> ";
            tableHtml += "<button class='btn btn-outline-danger' onclick='deleteUser(" + user.id + ")'>Supprimer</button>";
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
        if (user) {
            // Préremplir le formulaire avec les données de l'utilisateur
            document.getElementById('editUserId').value = user.id;
            document.getElementById('editUserFirstName').value = user.firstName;
            document.getElementById('editUserName').value = user.name;
            document.getElementById('editUserUserName').value = user.userName;
            document.getElementById('editUserMail').value = user.mail;
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
    function deleteUser(id) {
        if(confirm('Voulez-vous vraiment supprimer cet utilisateur ?')) {
            let formData = new FormData();
            formData.append('id', id);
            fetch("../pages/delete_user.php", {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if(data.success) {
                        alert('Utilisateur et ses données associées supprimés avec succès.');
                        loadContent('utilisateurs');
                    } else {
                        alert('Erreur lors de la suppression.');
                    }
                })
                .catch(error => console.error('Erreur:', error));
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
            <td>
                <button class='btn btn-outline-primary' onclick='editReponse(${signalement.idReponse})'>Modifier</button>
                <button class='btn btn-outline-danger' onclick='deleteReponse(${signalement.idReponse})'>Supprimer</button>
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
        fetch(`get_reponse_data.php?id=${idReponse}`)
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
            fetch('delete_reponse.php', {
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

        fetch('update_reponse.php', {
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



</script>
</body>
</html>