<?php
/**
 * Script pour stopper un don mensuel.
 *
 * Ce script permet à un utilisateur de stopper un don mensuel qu'il a précédemment initié.
 * Lorsqu'un don mensuel est arrêté, la base de données est mise à jour pour marquer ce changement.
 *
 * @package balance_ton_bully
 * @subpackage donations
 */
session_start();
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Vérification si l'identifiant du don est fourni
if (isset($_POST['donId'])) {
    $donId = $_POST['donId']; // Récupération de l'identifiant du don
    try {
        // Préparation de la requête pour mettre à jour le statut du don
        $stmt = $dbh->prepare("UPDATE Dons SET stopper_don_mensuel = TRUE, date_arret_don_mensuel = CURDATE() WHERE id = ?");
        // Exécution de la requête
        $stmt->execute([$donId]);
        // Envoi d'une réponse JSON indiquant le succès de l'opération
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        // Gestion des erreurs avec envoi d'une réponse JSON contenant le message d'erreur
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    // Réponse en cas d'absence de l'identifiant du don
    echo json_encode(['success' => false, 'error' => 'No donId provided']);
}