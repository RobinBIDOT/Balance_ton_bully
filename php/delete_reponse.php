<?php
/**
 * Suppression d'une réponse sur un forum.
 *
 * Ce script est utilisé par les administrateurs ou modérateurs pour supprimer une réponse dans le forum.
 * Il reçoit l'ID de la réponse à supprimer sous forme de JSON, effectue la suppression dans la base de données,
 * et renvoie un résultat sous forme de JSON pour indiquer le succès ou l'échec de l'opération.
 *
 * @package balance_ton_bully
 * @subpackage forum
 */
session_start();
// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Définition du type de contenu en tant que JSON
header('Content-Type: application/json');

// Récupération des données JSON envoyées par le client
$data = json_decode(file_get_contents("php://input"), true);

// Vérification de la présence de l'ID de la réponse
if (isset($data['id'])) {
    $idReponse = $data['id'];

    // Tentative de suppression des signalements associés
    $stmt = $dbh->prepare("DELETE FROM signalements WHERE id_reponse = ?");
    $stmt->execute([$idReponse]);

    // Tentative de suppression de la réponse
    $stmt = $dbh->prepare("DELETE FROM reponses_forum WHERE id_reponse = ?");
    $result = $stmt->execute([$idReponse]);  // Assignation du résultat de l'exécution

    // Vérification du résultat de la requête
    if ($result) {
        // Réponse en cas de succès
        echo json_encode(['success' => true]);
    } else {
        // Réponse en cas d'échec de la suppression
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
    }
} else {
    // Réponse si l'ID n'est pas fourni
    echo json_encode(['success' => false, 'error' => 'Identifiant non fourni']);
}
error_log('Tentative de suppression de la réponse ID: ' . $idReponse);