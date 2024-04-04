<?php
/**
 * Script pour récupérer le contenu d'une réponse spécifique dans un forum.
 *
 * Ce script est utilisé pour obtenir les détails d'une réponse donnée dans le forum à partir de son identifiant.
 * Il est principalement destiné à être utilisé pour la fonctionnalité d'édition des réponses par les administrateurs.
 *
 * @package balance_ton_bully
 * @subpackage forum
 * @author Robin
 *
 * @param int $_GET['id'] - L'identifiant de la réponse dont on souhaite récupérer les informations.
 * @return json - Renvoie un objet JSON contenant les détails de la réponse ou un message d'erreur.
 */
session_start();
include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_GET['id'])) {
    $idReponse = $_GET['id'];
    $stmt = $dbh->prepare("SELECT contenu FROM reponses_forum WHERE id_reponse = ?");
    $stmt->execute([$idReponse]);
    $reponse = $stmt->fetch(PDO::FETCH_ASSOC);

    echo json_encode($reponse);
} else {
    echo json_encode(['error' => 'Identifiant non fourni']);
}