<?php
/**
 * Script de traitement des paiements des dons
 */
session_start();
include('../php/tools/functions.php');
$dbh = dbConnexion();


/**
 * Redirige vers la page de don si les données de don ne sont pas présentes en session
 */
if (!isset($_SESSION['donData'])) {
    $_SESSION['errorMessage'] = "Aucune donnée de don n'est disponible. Veuillez recommencer le processus de don.";
    header('Location: dons.php');
    exit;
}

$donData = $_SESSION['donData'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validation des données
    if (!filter_var($donData['email'], FILTER_VALIDATE_EMAIL)) {
        echo "<script>alert('Adresse email non valide');</script>";
        exit;
    }

    if (floatval($donData['montant']) <= 0) {
        echo "<script>alert('Le montant doit être positif');</script>";
        exit;
    }

    try {
        if (insertDonation($dbh, $donData)) {
            echo "<script>alert('Votre paiement est accepté. Vous recevrez bientôt un email de confirmation.');</script>";
            unset($_SESSION['donData']); // Supprimer les données de don de la session après le paiement
        } else {
            throw new Exception('Erreur lors de l\'insertion des données dans la base de données.');
        }
    } catch (Exception $e) {
        error_log($e->getMessage());
        echo "<script>alert('Erreur lors du traitement du paiement.');</script>";
    }
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
