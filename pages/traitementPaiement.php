<?php
/**
 * traitementPaiement.php
 * Script de traitement des paiements des dons pour le site "Balance Ton Bully".
 */

try {
    // Inclusion des fonctions et connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();

    // Démarrage de la session
    session_start();

    echo "<pre>Données de la session : ";
    var_dump($_SESSION);
    echo "</pre>";

    echo "<pre>Données du formulaire : ";
    var_dump($_POST);
    echo "</pre>";
    /**
     * Redirection si les données de don ne sont pas présentes en session.
     */
//    if (!isset($_SESSION['donData'])) {
//        $_SESSION['errorMessage'] = "Aucune donnée de don n'est disponible. Veuillez recommencer le processus de don.";
//        header('Location: ../pages/dons.php');
//        exit;
//    }

    // Initialisation des variables
    $errorMessage = "";
    $nomCarte = "";
    $numeroCarte = "";
    $dateExpiration = "";
    $cvv = "";

    // Récupération des données de don de la session
    $donData = $_SESSION['donData'];

    /**
     * Traitement du formulaire lorsque soumis.
     */
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Récupération et validation des données du formulaire de don
        $typeDon = $_POST['typeDon'] ?? 'Don ponctuel';
        $montant = isset($_POST['montant']) && $_POST['montant'] != "" ? $_POST['montant'] : NULL;
        $montantLibre = isset($_POST['montantLibre']) && $_POST['montantLibre'] != "" ? $_POST['montantLibre'] : NULL;
        $prenom = $_POST['prenom'] ?? '';
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $dateNaissance = $_POST['dateNaissance'] ?? '';
        $adresse = $_POST['adresse'] ?? '';
        $codePostal = $_POST['codePostal'] ?? '';
        $ville = $_POST['ville'] ?? '';
        $pays = $_POST['pays'] ?? '';
        $estOrganisme = isset($_POST['payerOrganisme']) ? 1 : 0; // Conversion en 1 (true) ou 0 (false)
        $raisonSociale = $_POST['raisonSociale'] ?? NULL;
        $siren = $_POST['siren'] ?? NULL;
        $formeJuridique = $_POST['formeJuridique'] ?? NULL;

        // Validation de l'adresse email
        if (!filter_var($donData['email'], FILTER_VALIDATE_EMAIL)) {
            $errorMessage = "Adresse email non valide.";
        } elseif (floatval($donData['montant']) <= 0) {
            $errorMessage = "Le montant doit être positif.";
        } else {
            // Récupération des données du formulaire de paiement
            $nomCarte = htmlspecialchars($_POST['nomCarte'] ?? "");
            $numeroCarte = htmlspecialchars($_POST['numeroCarte'] ?? "");
            $dateExpiration = htmlspecialchars($_POST['dateExpiration'] ?? "");
            $cvv = htmlspecialchars($_POST['cvv'] ?? "");

            // Validation des données de paiement
            if (empty($nomCarte) || empty($numeroCarte) || empty($dateExpiration) || empty($cvv)) {
                $errorMessage = "Veuillez remplir tous les champs du formulaire.";
            } elseif (!preg_match("/^[a-zA-Z ]*$/", $nomCarte)) {
                $errorMessage = "Le nom sur la carte est invalide.";
            } elseif (!preg_match("/^\d{2}\/\d{2}$/", $dateExpiration)) {
                $errorMessage = "Le format de la date d'expiration est invalide.";
            } elseif (!preg_match("/^\d{3}$/", $cvv)) {
                $errorMessage = "Le format du CVV est invalide.";
            } else {
                // Validation de la date d'expiration
                $dateExpParts = explode('/', $dateExpiration);
                $currentYear = date('y');
                $currentMonth = date('m');
                if ($dateExpParts[1] < $currentYear || ($dateExpParts[1] == $currentYear && $dateExpParts[0] < $currentMonth)) {
                    $errorMessage = "La date d'expiration est invalide ou déjà expirée.";
                }
            }

            // Insertion du don dans la base de données si pas d'erreur
            if (empty($errorMessage)) {
                try {
                    if (insertDonation($dbh, $donData)) {
                        echo "<script>alert('Votre paiement est accepté. Vous recevrez bientôt un email de confirmation.');</script>";
                        unset($_SESSION['donData']); // Nettoyage de la session
                    } else {
                        throw new Exception('Erreur lors de l\'insertion des données dans la base de données.');
                    }
                } catch (Exception $e) {
                    error_log($e->getMessage());
                    $errorMessage = 'Erreur lors du traitement du paiement.';
                }
            }
        }
    }
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
    exit();
}
    /**
     * Insère un don dans la base de données
     *
     * @param PDO $dbh Connexion à la base de données
     * @param array $data Données du don à insérer
     * @return bool True si l'insertion est réussie, sinon false
     */
    function insertDonation($dbh, $data) {
        $sql = "INSERT INTO Dons (type_don, montant, montant_libre, prenom, nom, email, date_naissance, adresse, code_postal, ville, pays, est_organisme, raison_sociale, siren, forme_juridique) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $dbh->prepare($sql);

        // Association des paramètres
        $stmt->bindParam(1, $data['typeDon']);
        $stmt->bindParam(2, $data['montant']);
        $stmt->bindParam(3, $data['montantLibre']);
        $stmt->bindParam(4, $data['prenom']);
        $stmt->bindParam(5, $data['nom']);
        $stmt->bindParam(6, $data['email']);
        $stmt->bindParam(7, $data['dateNaissance']);
        $stmt->bindParam(8, $data['adresse']);
        $stmt->bindParam(9, $data['codePostal']);
        $stmt->bindParam(10, $data['ville']);
        $stmt->bindParam(11, $data['pays']);
        $stmt->bindParam(12, $data['estOrganisme'], PDO::PARAM_BOOL);
        $stmt->bindParam(13, $data['raisonSociale']);
        $stmt->bindParam(14, $data['siren']);
        $stmt->bindParam(15, $data['formeJuridique']);

        return $stmt->execute();

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
<?php include('../includes/headerNav.php'); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="blue-background">
                <h2 class="text-center">Confirmation de Paiement</h2>
                <?php
                    // Vérification des données du formulaire de don
                    $typeDon = $_POST['typeDon'] ?? 'Don ponctuel';
                    $montant = $_POST['montant'] ?? 0;
                    $montantLibre = $_POST['montantLibre'] ?? 0;
                    echo "<p>Type de don: " . htmlspecialchars($typeDon) . "</p>";
                    echo "<p>Montant Total: " . htmlspecialchars($montant) . " €</p>";
                ?>
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
                    <div class="modal-footer d-flex justify-content-between">
                        <button type="submit" class="btn btn-primary">Confirmer le paiement</button>
                        <!-- Bouton pour le paiement PayPal (pour l'affichage seulement) -->
                        <button type="button" class="btn btn-secondary me-2">Payer avec PayPal</button>
                        <button type="button" class="btn btn-secondary" onclick="window.history.back();">Annuler</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>
</body>
</html>