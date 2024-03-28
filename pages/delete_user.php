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

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    error_log("ID reçu : " . $id);

    try {
        $dbh->beginTransaction();

        // Vérifier si l'utilisateur existe
        $checkStmt = $dbh->prepare("SELECT COUNT(*) FROM utilisateurs WHERE id = ?");
        $checkStmt->execute([$id]);
        $userExists = $checkStmt->fetchColumn() > 0;

        if (!$userExists) {
            echo json_encode(['success' => false, 'error' => 'Utilisateur introuvable']);
            error_log("Erreur : Utilisateur introuvable pour l'ID : " . $id);
            $dbh->rollBack();
            exit;
        }

        // Suppression de l'utilisateur
        $stmt = $dbh->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$id]);
        if ($stmt->errorCode() != '00000') {
            error_log("Erreur SQL lors de la suppression dans utilisateurs : " . implode(";", $stmt->errorInfo()));
        }

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true]);
            error_log("Utilisateur supprimé avec succès pour l'ID : " . $id);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression ou utilisateur déjà supprimé']);
            error_log("Erreur ou utilisateur déjà supprimé pour l'ID : " . $id);
        }

        $dbh->commit();
    } catch (PDOException $e) {
        $dbh->rollBack();
        error_log("Erreur de transaction : " . $e->getMessage());
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la transaction']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Aucun ID fourni']);
    error_log("Erreur : Aucun ID fourni dans la requête POST");
}