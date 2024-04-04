<?php
/**
* Traitement du signalement d'une réponse dans un forum.
*
* Ce script PHP est appelé via une requête POST contenant l'identifiant d'une réponse.
* Il insère un enregistrement dans la table 'signalements' pour signaler une réponse spécifique.
*
* @package balance_ton_bully
* @subpackage forum
*/
session_start();
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Vérification de la présence de l'identifiant de la réponse
if (isset($_POST['idReponse'])) {
// Récupération de l'identifiant de la réponse à partir de la requête POST
$idReponse = $_POST['idReponse'];

// Préparation de la requête d'insertion du signalement
$stmt = $dbh->prepare("INSERT INTO signalements (id_reponse) VALUES (?)");

// Exécution de la requête et récupération du résultat
$result = $stmt->execute([$idReponse]);

// Vérification du résultat de la requête
if ($result) {
// En cas de succès, envoyer une réponse positive en JSON
echo json_encode(['success' => true]);
} else {
// En cas d'échec, envoyer une réponse négative avec un message d'erreur
echo json_encode(['success' => false, 'error' => 'Erreur lors de l\'insertion du signalement']);
}
} else {
// En cas d'absence de l'identifiant de la réponse, envoyer une réponse d'erreur
echo json_encode(['success' => false, 'error' => 'Identifiant de réponse non fourni']);
}
