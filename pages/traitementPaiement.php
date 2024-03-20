<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

echo "<pre>Données de la session : ";
var_dump($_SESSION);
echo "</pre>";

echo "<pre>Données du formulaire : ";
var_dump($_POST);
echo "</pre>";


// Si le bouton "Confirmer le paiement" est cliqué
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirmerPaiement'])) {
    $donId = $_SESSION['donId'];
    // Initialisation des variables
    $nomCarte = $_POST['nomCarte'] ?? '';
    $numeroCarte = $_POST['numeroCarte'] ?? '';
    $dateExpiration = $_POST['dateExpiration'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    // Validation des données de paiement
    if (empty($nomCarte) || empty($numeroCarte) || empty($dateExpiration) || empty($cvv)) {
        $errorMessage = "Veuillez remplir tous les champs du formulaire.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $nomCarte)) {
        $errorMessage = "Le nom sur la carte est invalide.";
    } elseif (!preg_match("/^\d{16}$/", $numeroCarte)) {
        $errorMessage = "Le format du numéro de la carte est invalide.";
    } elseif (!preg_match("/^\d{2}\/\d{4}$/", $dateExpiration)) {
        $errorMessage = "Le format de la date d'expiration est invalide.";
    } elseif (!preg_match("/^\d{3}$/", $cvv)) {
        $errorMessage = "Le format du CVV est invalide.";
    } else {
        // Validation de la date d'expiration
        list($expMonth, $expYear) = explode('/', $dateExpiration);
        $currentYear = date('Y');
        $currentMonth = date('m');
        if ($expYear < $currentYear || ($expYear == $currentYear && $expMonth < $currentMonth)) {
            $errorMessage = "La date d'expiration est invalide ou déjà expirée.";
        }
    }

    // Requête pour mettre à jour le statut du paiement
    $query = "UPDATE Dons SET est_paye = TRUE WHERE id = ?";
    $stmt = $dbh->prepare($query);
    $stmt->bindParam(1, $donId);

    if ($stmt->execute()) {
        $_SESSION['paiementEffectue'] = true;
    } else {
        $_SESSION['paiementEffectue'] = false;
        echo "<script>alert('Erreur lors de la confirmation du paiement.');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de Paiement</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de paiement</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php'); ?>
<div class="container mt-5 mx-auto max-w-6xl blue-background">
    <h2>Confirmation du Paiement</h2>
    <?php
        // Vérification des données du formulaire de don
        $typeDon = $_POST['typeDon'] ?? 'Don ponctuel';
        // Décider quel montant utiliser : montant fixe ou montant libre
        $montant = $_POST['montant'] ?? 0;
        $montantLibre = $_POST['montantLibre'] ?? 0;
        $montantFinal = $montantLibre > 0 ? $montantLibre : $montant; // Utiliser montant libre si disponible, sinon montant fixe
        echo "<p>Type de don: " . htmlspecialchars($typeDon) . "</p>";
        echo "<p>Montant Total: " . htmlspecialchars($montantFinal) . " €</p>";
        if (isset($_SESSION['paiementEffectue'])) {
            if ($_SESSION['paiementEffectue'] === true) {
                echo "<div class='alert alert-success'>Paiement effectué avec succès.</div>";
            } else {
                echo "<div class='alert alert-danger'>Échec du paiement. Veuillez réessayer.</div>";
            }
            unset($_SESSION['paiementEffectue']); // Nettoyer la variable de session après l'affichage
        }
    ?>
    <form action="../pages/traitementPaiement.php" method="post">
        <div class="mb-3">
            <label for="nomCarte" class="form-label">Nom sur la carte</label>
            <input type="text" class="form-control" id="nomCarte" name="nomCarte" placeholder="PRENOM NOM" required>
        </div>
        <div class="mb-3">
            <label for="numeroCarte" class="form-label">Numéro de la carte</label>
            <input type="text" class="form-control" id="numeroCarte" name="numeroCarte" placeholder="XXXX XXXX XXXX XXXX" required>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="dateExpiration" class="form-label">Date d'expiration</label>
                <input type="text" class="form-control" id="dateExpiration" name="dateExpiration" placeholder="MM/AA" pattern="\d{2}/\d{2}" title="Format MM/AA" required>
            </div>
            <div class="col">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" placeholder="XXX" pattern="\d{3}" title="Le CVV doit être composé de 3 chiffres" required>
            </div>
        </div>
        <div class="modal-footer d-flex justify-content-around">
            <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
            <!-- Bouton pour le paiement PayPal -->
            <button type="button" class="btn btn-secondary me-2">Payer avec PayPal</button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
        </div>
    </form>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>