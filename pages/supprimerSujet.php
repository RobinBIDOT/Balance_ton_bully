<?php
session_start();

if (!isset($_SESSION['pseudo'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: ../php/connexion.php");
    exit;
}

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $idSujet = $_GET['id'];

    // Inclure le fichier de connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();

    // Vérifier si l'utilisateur est l'auteur du sujet
    $sql = "SELECT * FROM sujets_forum WHERE id_sujet = :idSujet AND id_utilisateur = :idUtilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
    $stmt->bindParam(':idUtilisateur', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

    // Si l'utilisateur est l'auteur du sujet, supprimer le sujet
    if ($stmt->rowCount() > 0) {
        $sqlDelete = "DELETE FROM sujets_forum WHERE id_sujet = :idSujet";
        $stmtDelete = $dbh->prepare($sqlDelete);
        $stmtDelete->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
        $stmtDelete->execute();

        // Rediriger l'utilisateur vers la page d'accueil du forum après la suppression
        header("Location: accueilForum.php");
        exit;
    } else {
        // Rediriger l'utilisateur vers la page d'accueil du forum avec un message d'erreur s'il n'est pas l'auteur du sujet
        header("Location: accueilForum.php?erreur=1");
        exit;
    }
} else {
    // Rediriger l'utilisateur vers la page d'accueil du forum si l'ID du sujet n'est pas valide
    header("Location: accueilForum.php?erreur=2");
    exit;
}
?>
