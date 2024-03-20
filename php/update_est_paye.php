<?php
include('tools/functions.php');

$donId = $_POST['donId'] ?? null;

if ($donId) {
    try {
        $dbh = dbConnexion();

        // Requête SQL pour mettre à jour la colonne est_paye à true
        $query = "UPDATE Dons SET est_paye = TRUE WHERE id = ?";
        $stmt = $dbh->prepare($query);
        $stmt->bindParam(1, $donId);
        $stmt->execute();

        // Redirection vers la page de gestion des dons après la mise à jour
        header("Location: ../pages/dons.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de la mise à jour de la base de données : " . $e->getMessage();
    }
} else {
    echo "ID de don non spécifié.";
}