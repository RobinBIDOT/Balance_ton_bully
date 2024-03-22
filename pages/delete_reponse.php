<?php
/**
 * Script pour la suppression d'une réponse dans un forum.
 *
 * Ce script est utilisé pour supprimer une réponse spécifique dans un forum, identifiée par son ID.
 * Il est principalement destiné à être utilisé par les administrateurs ou les modérateurs du forum
 * pour maintenir la qualité et la pertinence des discussions. Le script récupère l'ID de la réponse
 * à partir d'une requête JSON, exécute une requête SQL pour la suppression, et retourne un résultat
 * sous forme de JSON indiquant le succès ou l'échec de l'opération.
 *
 * @package balance_ton_bully
 * @subpackage forum
 * @author Robin
 *
 * @param int $data['id'] - Identifiant de la réponse à supprimer.
 * @return json - Renvoie un objet JSON indiquant le succès ou l'échec de la suppression.
 */


include('../php/tools/functions.php');
$dbh = dbConnexion();

$data = json_decode(file_get_contents("php://input"), true);

if (isset($data['id'])) {
    $idReponse = $data['id'];
    $stmt = $dbh->prepare("DELETE FROM reponses_forum WHERE id_reponse = ?");
    $result = $stmt->execute([$idReponse]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la suppression']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Identifiant non fourni']);
}