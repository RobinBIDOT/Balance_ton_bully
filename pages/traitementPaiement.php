<?php
/**
 * Script de confirmation de paiement pour les dons.
 *
 * Ce script affiche les détails d'un don en attente de paiement et intègre un bouton de paiement PayPal.
 * Il utilise les informations de la session pour récupérer les détails du don depuis la base de données.
 * Le script affiche également des informations pour le débogage et intègre un bouton PayPal pour le paiement.
 *
 * PHP version 7.4
 *
 * @category   Donation
 * @package    BalanceTonBully
 * @subpackage Payment
 */

// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Affichage des données de la session et du formulaire pour le débogage
//echo "<pre>Données de la session : ";
//var_dump($_SESSION);
//echo "</pre>";
//
//echo "<pre>Données du formulaire : ";
//var_dump($_POST);
//echo "</pre>";

$clientId = 'Aa3n6pC_-rkPL6a2XOijOnQFSMwBVz8RtpwX4qNjLtT17RPPG5TgdkxnXlTV1Ry1_vceUjJrpCWFBhJe';

// Vérification des données de session
$donId = $_SESSION['donId'] ?? null;

if ($donId) {
    try {
        // Requête SQL pour récupérer les données du don en fonction de son ID
        $query = "SELECT type_don, montant, montant_libre FROM Dons WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $donId);
        $stmt->execute();

        // Récupération des résultats
        $don = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($don) {
            // Affichage des données du don
            $typeDon = $don['type_don'];
            $montant = $don['montant'];
            $montantLibre = $don['montant_libre'];

            // Si le montant est défini, il est utilisé, sinon c'est le montant libre
            $montantFinal = ($montant !== null && $montant > 0) ? $montant : $montantLibre;

            // Affichage des informations
//            echo "<p>Type de don : $typeDon</p>";
//            echo "<p>Montant total du don : $montantFinal €</p>";
        } else {
            echo "<p>Aucun don trouvé avec l'ID : $donId</p>";
        }
    } catch (PDOException $e) {
        echo "<p>Erreur lors de la récupération des données du don : " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>Aucun ID de don trouvé dans la session</p>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page de paiement</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleDons.css">
    <script src="https://www.paypal.com/sdk/js?client-id=<?php echo $clientId; ?>"></script>
</head>
<body>
<?php include('../includes/headerNav.php'); ?>
<div class="container mt-5 mx-auto max-w-8xl border border-3 border-primary shadow">
    <div class="text-center mt-5">
        <h2>Confirmation du Paiement</h2>
        <p>Type de don : <?php echo $typeDon; ?></p>
        <p>Montant total du don : <?php echo $montantFinal; ?> €</p>
        <div class="d-flex justify-content-center mt-3">
            <div id="paypal-button-container" class="w-50"></div>
        </div>
        <div class="d-flex justify-content-center mt-3">
            <div id="paypal-button-container" class="w-50"></div>
        </div>
        <script>
            paypal.Buttons({
                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: {
                                value: '<?php echo $montantFinal; ?>'
                            }
                        }]
                    });
                },
                onApprove: function(data, actions){
                    return actions.order.capture().then(function(details){
                        alert("Transaction OK : " + details.payer.name.given_name);
                    });
                },
                onError: function (err){
                    console.error('Payment Error :', err);
                    alert ("Paiement échoué !");
                }
            }).render("#paypal-button-container");
        </script>
        <div class="text-center mt-3 mb-3">
            <form method="post" action="../php/update_est_paye.php">
                <input type="hidden" name="donId" value="<?php echo $donId; ?>">
                <button type="submit" class="btn btn-primary">Marquer comme payé</button>
            </form>
            <button class="btn btn-secondary mt-3" onclick="window.location.href='../pages/dons.php';">Annuler</button>
        </div>
    </div>
</div>
<?php include('../includes/footer.php'); ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>