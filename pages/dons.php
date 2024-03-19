<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../php/tools/functions.php');

// Vérifie si l'utilisateur est connecté avant de procéder
if (!isset($_SESSION['nickName'])) {
    // Redirige vers la page de connexion si non connecté
    header('Location: ../php/connexion.php');
    exit;
}

// Traitement du formulaire de don
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    $donData = [
        'typeDon' => $_POST['typeDon'] ?? 'Don ponctuel',
        'montant' => $_POST['montant'] ?? 0,
        'montantLibre' => $_POST['montantLibre'] ?? 0,
        'prenom' => $_POST['prenom'] ?? '',
        'nom' => $_POST['nom'] ?? '',
        'email' => $_POST['email'] ?? '',
        'dateNaissance' => $_POST['dateNaissance'] ?? '',
        'adresse' => $_POST['adresse'] ?? '',
        'codePostal' => $_POST['codePostal'] ?? '',
        'ville' => $_POST['ville'] ?? '',
        'pays' => $_POST['pays'] ?? '',
        'estOrganisme' => isset($_POST['payerOrganisme']),
        'raisonSociale' => $_POST['raisonSociale'] ?? '',
        'siren' => $_POST['siren'] ?? '',
        'formeJuridique' => $_POST['formeJuridique'] ?? ''
    ];

    // Validation des données, par exemple, validation de l'email
    if (!filter_var($donData['email'], FILTER_VALIDATE_EMAIL)) {
        // Afficher un message d'erreur ou le traiter comme nécessaire
        echo "<script>alert('Adresse email non valide');</script>";
    } else {
        // Stocker les données de don dans la session
        $_SESSION['donData'] = $donData;

        // Rediriger vers la page de traitement du paiement
        header('Location: ../pages/traitementPaiement.php');
        exit;
    }
}
// Débogage
echo "<pre>Session Data : ";
var_dump($_SESSION);
echo "</pre>";
echo "<pre>Données du formulaire : ";
var_dump($_POST);
echo "</pre>";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de dons</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php'); ?>
<div class="container-fluid mt-5 custom-container">
    <form id="formDon" action="../pages/traitementPaiement.php" method="post">
        <div class="row">
            <!-- Colonne de gauche (Type de don, Montant) -->
            <div class="col-md-4">
                <!-- Section Type de Don -->
                <div class="blue-background">
                    <h3>Mon don</h3>
                    <div class="mb-3">
                        <button type="button" id="donUneFois" class="btn btn-primary">Je donne une fois</button>
                        <button type="button" id="donMensuel" class="btn btn-primary">Je donne tous les mois</button>
                    </div>
                    <div class="mb-3">
                        <label for="montant">Montant :</label><br>
                        <input type="radio" name="montant" value="5"> 5 €
                        <input type="radio" name="montant" value="20"> 20 €
                        <input type="radio" name="montant" value="50"> 50 €
                        <input type="radio" name="montant" value="150"> 150 €<br>
                        <label for="montantLibre" class="form-label mt-3">Montant libre</label>
                        <div class="input-group">
                            <input type="text" class="form-control" id="montantLibre" name="montantLibre" placeholder="Montant libre">
                            <button type="button" id="confirmMontantLibre" class="btn btn-primary">Confirmer</button>
                        </div>
                    </div>
                </div>
                <div class="blue-background mt-5">
                    <h3>Réduction fiscale</h3>
                    <p id="infoReduction"></p>
                    <button type="button" id="btnParticulier" class="btn btn-primary">Particulier</button>
                    <button type="button" id="btnOrganisme" class="btn btn-primary">Organisme</button>
                    <p class="mt-3" id="infoReductionCategorie"></p>
                </div>
            </div>
            <!-- Colonne centrale (Coordonnées) -->
            <div class="col-md-4">
                <div class="blue-background" id="mesCoordonnees">
                    <h3>Mes coordonnées</h3>
                    <!-- Formulaire coordonnées -->
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" id="payerOrganisme">
                        <label class="form-check-label" for="payerOrganisme">Payer en tant qu'organisme</label>
                    </div>
                    <!-- Ajout du formulaire pour les organismes -->
                    <div id="champsOrganisme" style="display: none;">
                        <div class="mb-3">
                            <label for="raisonSociale" class="form-label">Raison sociale</label>
                            <input type="text" class="form-control mb-2" id="raisonSociale" name="raisonSociale" placeholder="Raison sociale">
                        </div>
                        <div class="mb-3">
                            <label for="siren" class="form-label">SIREN</label>
                            <input type="text" class="form-control mb-2" id="siren" name="siren" placeholder="SIREN">
                        </div>
                        <div class="mb-3">
                            <label for="formeJuridique" class="form-label">Forme juridique</label>
                            <input type="text" class="form-control mb-2" id="formeJuridique" name="formeJuridique" placeholder="Forme juridique">
                        </div>
                    </div>
                    <!-- Fin du formulaire pour les organismes -->
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="prenom" class="form-label">Prénom</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" required>
                        </div>
                        <div class="col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="dateNaissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" required>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="codePostal" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="codePostal" name="codePostal" required>
                        </div>
                        <div class="col">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pays" class="form-label">Pays</label>
                        <input type="text" class="form-control" id="pays" name="pays" required>
                    </div>
                </div>
            </div>
            <!-- Colonne de droite (Récapitulatif) -->
            <div class="col-md-4">
                <div class="blue-background">
                    <h3>Mon récapitulatif</h3>
                    <!-- Section Récapitulatif -->
                    <div class="mb-3 form-check">
                        <p id="typeDon"></p>
                        <p id="montantTotal"></p>
                        <input type="checkbox" class="form-check-input" id="acceptConditions">
                        <label class="form-check-label" for="acceptConditions">J’accepte les Conditions Générales d’Utilisation du service et j’ai lu la charte de confidentialité *</label>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary">Valider et payer</button>
                </div>
                <div class="blue-background mt-5">
                    <p>Plateforme de paiement 100 % sécurisée</p>
                    <p>Toutes les informations bancaires pour traiter ce paiement sont totalement sécurisées. Grâce au cryptage SSL de vos données bancaires, vous êtes assurés de la fiabilité de vos transactions sur notre site.</p>
                </div>
            </div>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="../js/scriptDons.js"></script>
<!--<script>alert('Valeur du champ "email" : ' + document.getElementById('email').value);-->
</script>
</body>
</html>
