<?php
/**
 * Script pour la mise à jour d'une réponse dans un forum.
 *
 * Ce script reçoit l'identifiant et le nouveau contenu d'une réponse spécifique via une requête POST.
 * Il met à jour le contenu de la réponse dans la base de données.
 * Ce script est conçu pour permettre aux administrateurs de modifier les réponses dans le forum.
 *
 * @package balance_ton_bully
 * @subpackage forum
 * @author Robin
 *
 * @param int $_POST['id'] - L'identifiant de la réponse à mettre à jour.
 * @param string $_POST['contenu'] - Le nouveau contenu de la réponse.
 * @return json - Renvoie un objet JSON indiquant le succès ou l'échec de la mise à jour.
 */
session_start();

include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['id']) && isset($_POST['contenu'])) {
    $idReponse = $_POST['id'];
    $contenu = $_POST['contenu'];

    $stmt = $dbh->prepare("UPDATE reponses_forum SET contenu = ? WHERE id_reponse = ?");
    $result = $stmt->execute([$contenu, $idReponse]);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Données manquantes']);
}