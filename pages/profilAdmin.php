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
} else {
    echo json_encode(['success' => false, 'error' => 'No donId provided']);
}
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

<?php include('../includes/footer.php') ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script>
    // Rend les données PHP disponibles en tant qu'objet JavaScript
    var donsData = <?php echo $jsonDonsData; ?>;

    /**
     * Charge le contenu spécifique en fonction du type sélectionné.
     * @param {string} type - Le type de contenu à charger ('actualites', 'dons', 'utilisateurs', 'signalements', 'pro_sante').
     */
    function loadContent(type) {
        var contentArea = document.querySelector('.content-area');
        switch (type) {
            case 'actualites':
                contentArea.innerHTML = '<h2 class="text-center mb-4">Gestion des actualités</h2><p class="text-center mb-4">Ici, vous pouvez gérer les actualités du site.</p>';
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