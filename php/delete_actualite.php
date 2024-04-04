<?php
/**
 * Ce script est utilisé pour supprimer une actualité spécifiée par son identifiant.
 *
 * Ce script reçoit un identifiant d'actualité via une requête POST. Il effectue ensuite une opération
 * de suppression dans la base de données pour l'actualité correspondante.
 * Si la suppression réussit, le script renvoie un succès. En cas d'échec, il renvoie un échec.
 *
 * @package    balance_ton_bully
 * @subpackage admin
 */
session_start();
// Vérifiez que l'ID de l'actualité est fourni
if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Connectez-vous à la base de données et supprimez l'actualité
    include('../php/tools/functions.php');
    $dbh = dbConnexion();

    // Préparer la requête de suppression
    $stmt = $dbh->prepare("DELETE FROM actualites WHERE id_actualite = ?");
    // Exécuter la requête et renvoyer le résultat
    if ($stmt->execute([$id])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
} else {
    // Renvoyer un échec si aucun ID n'est fourni
    echo json_encode(['success' => false, 'error' => 'No id provided']);
}