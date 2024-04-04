<?php
/**
 * Script PHP pour supprimer une réponse dans un forum.
 *
 * Ce script permet de supprimer une réponse spécifique dans un forum en utilisant son identifiant.
 * Il est utilisé pour gérer le contenu du forum et maintenir un environnement sûr et respectueux.
 *
 * @package balance_ton_bully
 * @subpackage forum
 *
 * @param int $idReponse - L'identifiant de la réponse à supprimer.
 * @param int $idSujet - L'identifiant du sujet auquel la réponse appartient.
 * @return void
 */
session_start();
// Inclusion du fichier de connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Vérifier si l'ID de la réponse est passé en paramètre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idReponse = $_GET['id'];

    // Vérifier si l'ID du sujet est passé en paramètre
    if (isset($_GET['idSujet']) && is_numeric($_GET['idSujet'])) {
        $idSujet = $_GET['idSujet'];

        try {
            // Supprimer la réponse de la base de données
            $sqlDelete = "DELETE FROM reponses_forum WHERE id_reponse = :id";
            $stmtDelete = $dbh->prepare($sqlDelete);
            $stmtDelete->bindParam(':id', $idReponse, PDO::PARAM_INT);
            $stmtDelete->execute();

            // Redirection vers la page du sujet
            header('Location: ../pages/sujet.php?id=' . $idSujet);
            exit();
        } catch (PDOException $e) {
            // Gestion des erreurs PDO
            echo "Erreur lors de la suppression de la réponse : " . $e->getMessage();
        }
    } else {
        // Redirection vers la page d'accueil si l'ID du sujet n'est pas valide
        header('Location: ../php/index.php');
        exit();
    }
} else {
    // Redirection vers la page d'accueil si l'ID de la réponse n'est pas valide
    header('Location: ../php/index.php');
    exit();
}