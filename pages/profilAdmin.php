<?php
/**
 * Ce script PHP sert à préparer l'interface d'administration en récupérant les données nécessaires et en les rendant disponibles pour un traitement ultérieur en JavaScript.
 */
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérification si l'utilisateur est connecté et a le rôle d'admin
if (!isset($_SESSION['nickName']) || $_SESSION['roleId'] != 1) {
    // Redirection si l'utilisateur n'est pas admin ou n'est pas connecté
    header('Location: index.php'); // Redirige vers la page d'accueil ou de connexion
    exit();
}

// Préparation des données des dons pour JavaScript
$donsData = [];
if (isset($_SESSION['nickName']) && $_SESSION['roleId'] == 1) {
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
if (isset($_SESSION['nickName']) && $_SESSION['roleId'] == 1) {
    $stmt = $dbh->prepare("SELECT * FROM actualites ORDER BY date_publication DESC");
    $stmt->execute();
    $actualitesData = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Convertit les données PHP en JSON pour JavaScript
$jsonActualitesData = json_encode($actualitesData);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page en Construction</title>
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
<!-- Modal pour la modification d'une actualité -->
<div id="modalEditActu" class="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Modifier l'actualité</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
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
                        <label>Photo</label>
                        <input type="file" class="form-control" id="editActuPhoto" name="photo">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Annuler</button>
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

    // Données des actualités
    var actualitesData = <?php echo $jsonActualitesData; ?>;

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
                contentArea.innerHTML = '<h2 class="text-center mb-4">Gestion des utilisateurs</h2><p class="text-center mb-4">Ici, vous pouvez gérer les utilisateurs.</p>';
                break;
            case 'signalements':
                contentArea.innerHTML = '<h2 class="text-center mb-4">Voir les signalements</h2><p class="text-center mb-4">Ici, vous pouvez consulter les signalements.</p>';
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
            tableHtml += "<button class='btn btn-warning' onclick='editActu(" + actu.id_actualite + ")'>Modifier</button> ";
            tableHtml += "<button class='btn btn-danger' onclick='deleteActu(" + actu.id_actualite + ")'>Supprimer</button>";
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
                        loadContent('actualites');
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
                    loadContent('actualites');
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
</script>
</body>
</html>