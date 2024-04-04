<?php
/**
 * Script pour marquer un don comme payé.
 *
 * Ce script reçoit un identifiant de don et met à jour son statut dans la base de données,
 * en marquant le don comme payé.
 *
 * PHP version 7.4
 *
 * @category Payment
 * @package  MovEase
 */
session_start();
// Inclusion des outils et fonctions
include('tools/functions.php');

// Récupération de l'ID de don
$donId = $_POST['donId'] ?? null;

if ($donId) {
    try {
        $dbh = dbConnexion(); // Connexion à la base de données

        // Requête SQL pour mettre à jour la colonne est_paye à true
        $query = "UPDATE Dons SET est_paye = TRUE WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $donId); // Liaison du paramètre ID de don
        $stmt->execute();

        // Redirection vers la page de gestion des dons après la mise à jour
        header("Location: ../pages/dons.php");
        exit();
    } catch (PDOException $e) {
        // Gestion des erreurs et affichage d'un message en cas d'erreur
        echo "Erreur lors de la mise à jour de la base de données : " . $e->getMessage();
    }
} else {
    // Message d'erreur si l'ID de don n'est pas spécifié
    echo "ID de don non spécifié.";
}