<?php
session_start();
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Définir les variables d'erreur
$errorMessage = "";
$paiementError = false;

// Récupération des données de don depuis la session
$donData = isset($_SESSION['donData']) ? $_SESSION['donData'] : null;

// Vérifier si des données de don sont disponibles
if (!$donData) {
    $_SESSION['errorMessage'] = "Aucune donnée de don n'est disponible. Veuillez recommencer le processus de don.";
    header('Location: dons.php');
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nomCarte = isset($_POST['nomCarte']) ? htmlspecialchars($_POST['nomCarte']) : "";
    $numeroCarte = isset($_POST['numeroCarte']) ? htmlspecialchars($_POST['numeroCarte']) : "";
    $dateExpiration = isset($_POST['dateExpiration']) ? htmlspecialchars($_POST['dateExpiration']) : "";
    $cvv = isset($_POST['cvv']) ? htmlspecialchars($_POST['cvv']) : "";

    // Valider les données du formulaire (ajoutez vos propres validations)
    if (empty($nomCarte) || empty($numeroCarte) || empty($dateExpiration) || empty($cvv)) {
        $errorMessage = "Veuillez remplir tous les champs du formulaire.";
    } elseif (!preg_match("/^[a-zA-Z ]*$/", $nomCarte)) {
        $errorMessage = "Le nom sur la carte est invalide.";
    } elseif (!preg_match("/^\d{2}\/\d{2}$/", $dateExpiration)) {
        $errorMessage = "Le format de la date d'expiration est invalide.";
    } elseif (!preg_match("/^\d{3}$/", $cvv)) {
        $errorMessage = "Le format du CVV est invalide.";
    }

    // Si aucune erreur de validation, traiter le paiement
    if (!$errorMessage) {
        try {
            // Traitement du paiement (exemple)
            require_once('paiement/plateformeDePaiement.php');

            $paiement = new PlateformeDePaiement();
            $resultatPaiement = $paiement->effectuerPaiement(
                $donData['montantTotal'],
                $numeroCarte,
                $dateExpiration,
                $cvv
            );

            if ($resultatPaiement['success']) {
                // Enregistrer le don en base de données (exemple)
                $sql = "INSERT INTO dons (type_don, montant, nom_carte, numero_carte, date_expiration, cvv, email, date_don) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
                $stmt = $dbh->prepare($sql);
                $stmt->execute([
                    $donData['typeDon'],
                    $donData['montantTotal'],
                    $nomCarte,
                    $numeroCarte,
                    $dateExpiration,
                    $cvv,
                    $donData['email'],
                    date('Y-m-d')
                ]);

                // Envoyer un email de confirmation (exemple)
                // ...

                // Rediriger vers la page de remerciement
                header('Location: remerciementDon.php');
                exit;
            } else {
                $paiementError = true;
                $errorMessage = $resultatPaiement['message'];
            }
        } catch (Exception $e) {
            $errorMessage = "Une erreur est survenue lors du traitement du paiement. Veuillez réessayer ultérieurement.";
            $paiementError = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de paiement</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<div class="container">
    <h2>Confirmation de Paiement</h2>
    <p>Type de don: <?php echo htmlspecialchars($donData['typeDon']); ?></p>
    <p>Montant Total: <?php echo htmlspecialchars($donData['montantTotal']); ?> €</p>
    <form id="formPaiement" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <?php if ($errorMessage) { ?>
            <div class="alert alert-danger" role="alert">
                <?php echo $errorMessage; ?>
            </div>
        <?php } ?>
        <div class="mb-3">
            <label for="nomCarte" class="form-label">Nom sur la carte</label>
            <input type="text" class="form-control" id="nomCarte" name="nomCarte" value="<?php echo $nomCarte; ?>" required>
        </div>
        <div class="mb-3">
            <label for="numeroCarte" class="form-label">Numéro de la carte</label>
            <input type="text" class="form-control" id="numeroCarte" name="numeroCarte" value="<?php echo $numeroCarte; ?>" required>
        </div>
        <div class="mb-3 row">
            <div class="col">
                <label for="dateExpiration" class="form-label">Date d'expiration</label>
                <input type="text" class="form-control" id="dateExpiration" name="dateExpiration" value="<?php echo $dateExpiration; ?>" placeholder="MM/AA" pattern="\d{2}/\d{2}" title="Format MM/AA" required>
            </div>
            <div class="col">
                <label for="cvv" class="form-label">CVV</label>
                <input type="text" class="form-control" id="cvv" name="cvv" value="<?php echo $cvv; ?>" pattern="\d{3}" title="Le CVV doit être composé de 3 chiffres" required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
            <button type="button" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
        </div>
    </form>
</div>
</body>
</html>