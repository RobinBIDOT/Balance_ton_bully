<?php
/**
 * Script pour la suppression d'une réponse dans un forum.
 *
 * Ce script permet de supprimer une réponse spécifique dans le forum en utilisant son identifiant.
 * Il est destiné à être utilisé par les administrateurs pour gérer le contenu du forum.
 *
 * @package balance_ton_bully
 * @subpackage forum
 * @author Robin
 *
 * @param int $data['id'] - L'identifiant de la réponse à supprimer.
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