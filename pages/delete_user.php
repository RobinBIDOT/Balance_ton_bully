<?php
/**
 * Script pour la suppression d'un utilisateur spécifié par son identifiant.
 *
 * Ce script reçoit l'identifiant de l'utilisateur via une requête POST. Il effectue ensuite une opération
 * de suppression dans la base de données pour cet utilisateur.
 * Si la suppression réussit, il renvoie une réponse de succès, sinon une réponse d'erreur.
 *
 * @package balance_ton_bully
 * @subpackage admin
 */

include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['id'])) {
    $id = $_POST['id'];

    // Suppression des sujets et réponses associés à l'utilisateur
    $stmt = $dbh->prepare("DELETE FROM reponses_forum WHERE id_utilisateur = ?");
    $stmt->execute([$id]);

    $stmt = $dbh->prepare("DELETE FROM sujets_forum WHERE id_utilisateur = ?");
    $stmt->execute([$id]);

    // Suppression de l'utilisateur
    $stmt = $dbh->prepare("DELETE FROM utilisateurs WHERE id = ?");
    $stmt->execute([$id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucun ID fourni']);
}