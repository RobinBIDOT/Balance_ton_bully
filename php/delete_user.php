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
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    try {

        $stmt = $dbh->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        echo json_encode([
            'status' => 'success',
            'message' => "Utilisateur supprimé avec succès"
        ]);
    } catch (PDOException $e) {
        echo json_encode([
            'success' => false,
            'message' => "Erreur ou utilisateur déjà supprimé" . $e]);
    }
}
