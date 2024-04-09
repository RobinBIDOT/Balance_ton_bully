<?php
/**
 * Script de traitement du formulaire de dons.
 *
 * Ce script PHP gère le traitement des données saisies dans le formulaire de dons.
 * Il valide les données, les nettoie et les insère dans la base de données.
 * En cas de succès, il redirige l'utilisateur vers la page de paiement pour finaliser
 * le don. Si une erreur se produit lors de la validation ou de l'insertion des données,
 * un message d'erreur est affiché à l'utilisateur.
 *
 * @package balance_ton_bully
 * @subpackage dons
 */

// Activation de l'affichage des erreurs pour le débogage
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Initialisation de la variable de message d'erreur
$errorMessage = '';

// Traitement du formulaire de don
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération et nettoyage des données du formulaire
    if (isset($_POST['donUneFois'])) {
        $typeDon = 'Don ponctuel';
    } elseif (isset($_POST['donMensuel'])) {
        $typeDon = 'Don mensuel';
    } else {
        $typeDon = 'Don ponctuel';
    }
    $montant = isset($_POST['montant']) ? filter_var($_POST['montant'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;
    $montantLibre = isset($_POST['montantLibre']) ? filter_var($_POST['montantLibre'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION) : 0;
    $prenom = htmlspecialchars(trim($_POST['prenom']));
    $nom = htmlspecialchars(trim($_POST['nom']));
    // Validation de l'email
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email non valide.\\n');</script>";
    }
    $dateNaissance = $_POST['dateNaissance'];
    $adresse = htmlspecialchars(trim($_POST['adresse']));
    // Validation du code postal
    $codePostal = trim($_POST['codePostal']);
    if (!preg_match("/^\d{5}$/", $codePostal)) {
        echo "<script>alert('Le code postal doit contenir exactement 5 chiffres.\\n');</script>";
    } else {
        $codePostal = htmlspecialchars($codePostal);
    }
    $ville = htmlspecialchars(trim($_POST['ville']));
    $pays = htmlspecialchars(trim($_POST['pays']));
    $estOrganisme = isset($_POST['payerOrganisme']) ? 1 : 0;
    $raisonSociale = htmlspecialchars(trim($_POST['raisonSociale']));
    // Validation du SIREN pour les organismes
    $siren =  trim($_POST['siren']);
    if (!preg_match("/^\d{9}$/", $siren)) {
        echo "<script>alert('Le numéro SIREN doit contenir exactement 9 chiffres.\\n');</script>";
    }
    $formeJuridique = htmlspecialchars(trim($_POST['formeJuridique']));

    // S'il y a des erreurs, les afficher et arrêter l'exécution du script
    if (!empty($errorMessage)) {
        echo "<script>alert('$errorMessage');</script>";
    } else {
        // Insertion des données dans la base de données
        try {
            $query = "INSERT INTO Dons (type_don, montant, montant_libre, prenom, nom, email, date_naissance, adresse, code_postal, ville, pays, est_organisme, raison_sociale, siren, forme_juridique) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $dbh->prepare($query);

            // Liaison des paramètres
            $stmt->bindParam(1,$typeDon);
            $stmt->bindParam(2,$montant);
            $stmt->bindParam(3,$montantLibre);
            $stmt->bindParam(4,$prenom);
            $stmt->bindParam(5,$nom);
            $stmt->bindParam(6,$email);
            $stmt->bindParam(7,$dateNaissance);
            $stmt->bindParam(8,$adresse);
            $stmt->bindParam(9,$codePostal);
            $stmt->bindParam(10,$ville);
            $stmt->bindParam(11,$pays);
            $stmt->bindParam(12,$estOrganisme);
            $stmt->bindParam(13,$raisonSociale);
            $stmt->bindParam(14,$siren);
            $stmt->bindParam(15,$formeJuridique);

            if ($stmt->execute()) {
                // Stockage de l'ID du don dans la session
                $_SESSION['donId'] = $dbh->lastInsertId();
                header('Location: ../pages/traitementPaiement.php');
                exit;
            } else {
                echo "<script>alert('Erreur lors de l\'enregistrement du don.');</script>";
            }
        } catch (PDOException $e) {
            // Gestion des erreurs de base de données
            echo "<script>alert('Erreur de base de données: " . $e->getMessage() . "');</script>";
        }
    }
}
// Débogage
//echo "<pre>Session Data : ";
//var_dump($_SESSION);
//echo "</pre>";
//echo "<pre>Données du formulaire : ";
//var_dump($_POST);
//echo "</pre>";
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de dons</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php'); ?>
<div class="container-fluid mt-5 custom-container">
    <form id="formDon" method="post">
        <div class="row">
            <!-- Colonne de gauche (Type de don, Montant) -->
            <div class="col-md-4">
                <!-- Section Type de Don -->
                <div class="blue-background">
                    <h3 class="fw-bold mb-2" style="font-size: 2rem;">Mon don</h3>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" id="donUneFois">Je donne une fois</button>
                        <button type="button" class="btn btn-primary" id="donMensuel">Je donne tous les mois</button>
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
                    <h3 class="fw-bold mb-2" style="font-size: 2rem;">Réduction fiscale</h3>
                    <p id="infoReduction"></p>
                    <button type="button" id="btnParticulier" class="btn btn-primary">Particulier</button>
                    <button type="button" id="btnOrganisme" class="btn btn-primary">Organisme</button>
                    <p class="mt-3" id="infoReductionCategorie"></p>
                </div>
            </div>
            <!-- Colonne centrale (Coordonnées) -->
            <div class="col-md-4">
                <div class="blue-background" id="mesCoordonnees">
                    <h3  class="fw-bold mb-2" style="font-size: 2rem;">Mes coordonnées</h3>
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
                            <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
                        </div>
                        <div class="col">
                            <label for="nom" class="form-label">Nom</label>
                            <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" placeholder="exemple@exemple.fr" required>
                    </div>
                    <div class="mb-3">
                        <label for="dateNaissance" class="form-label">Date de naissance</label>
                        <input type="date" class="form-control" id="dateNaissance" name="dateNaissance" required>
                    </div>
                    <div class="mb-3">
                        <label for="adresse" class="form-label">Adresse</label>
                        <input type="text" class="form-control" id="adresse" name="adresse" placeholder="Adresse" required>
                    </div>
                    <div class="mb-3 row">
                        <div class="col">
                            <label for="codePostal" class="form-label">Code postal</label>
                            <input type="text" class="form-control" id="codePostal" name="codePostal" placeholder="75000" required>
                        </div>
                        <div class="col">
                            <label for="ville" class="form-label">Ville</label>
                            <input type="text" class="form-control" id="ville" name="ville" placeholder="Paris" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pays" class="form-label">Pays</label>
                        <input type="text" class="form-control" id="pays" name="pays" placeholder="France" required>
                    </div>
                </div>
            </div>
            <!-- Colonne de droite (Récapitulatif) -->
            <div class="col-md-4">
                <div class="blue-background">
                    <h3  class="fw-bold mb-2" style="font-size: 2rem;">Mon récapitulatif</h3>
                    <!-- Section Récapitulatif -->
                    <div class="mb-3 form-check">
                        <p id="typeDon"></p>
                        <p id="montantTotal"></p>
                        <input type="checkbox" class="form-check-input" id="acceptConditions">
                        <label class="form-check-label" for="acceptConditions">J’accepte les Conditions Générales d’Utilisation du service et j’ai lu la charte de confidentialité *</label>
                    </div>
                </div>
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-primary" id="validerPayer" disabled>Valider et payer</button>
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
<?php include('../includes/scriptLink.php') ?>
<script src="../js/scriptDons.js"></script>
</body>
</html>
