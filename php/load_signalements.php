<?php
/**
 * Script pour récupérer et afficher les signalements dans un forum.
 *
 * Ce script exécute une requête SQL pour récupérer des informations détaillées sur les signalements,
 * y compris les détails du sujet, de la réponse, et de l'auteur. Ces informations sont ensuite
 * utilisées pour afficher une liste des signalements dans l'interface d'administration.
 * Ce script est conçu pour permettre aux administrateurs de gérer les signalements dans le forum.
 *
 * @package balance_ton_bully
 * @subpackage forum
 * @author Robin
 *
 * @return json - Renvoie un tableau de signalements au format JSON, avec des détails sur le sujet,
 *                la réponse, l'auteur du sujet, l'auteur de la réponse, et la date du signalement.
 */
session_start();

include('../php/tools/functions.php');
$dbh = dbConnexion();

$sql = "SELECT 
            sj.titre AS titreSujet, 
            ua.userName AS nomAuteurSujet, 
            sj.date_creation AS dateCreationSujet,
            ub.userName AS nomAuteurReponse, 
            rf.date_creation AS dateReponse,
            rf.contenu AS contenuReponse,
            s.date_signalement AS dateSignalement,
            rf.id_reponse AS idReponse
        FROM signalements s
        JOIN reponses_forum rf ON s.id_reponse = rf.id_reponse
        JOIN sujets_forum sj ON rf.id_sujet = sj.id
        JOIN utilisateurs ua ON sj.id_utilisateur = ua.id
        JOIN utilisateurs ub ON rf.id_utilisateur = ub.id";

$stmt = $dbh->prepare($sql);
$stmt->execute();
$signalements = $stmt->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($signalements);