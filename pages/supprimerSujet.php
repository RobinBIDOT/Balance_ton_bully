<?php
session_start();

if (!isset($_SESSION['nickName'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: ../php/connexion.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id_sujet']) && is_numeric($_POST['id_sujet'])) {
    $idSujet = $_POST['id_sujet'];

    // Inclure le fichier de connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();

    // Supprimer les réponses associées au sujet
    $sqlDeleteReponses = "DELETE FROM reponses_forum WHERE id_sujet = :idSujet";
    $stmtDeleteReponses = $dbh->prepare($sqlDeleteReponses);
    $stmtDeleteReponses->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
    $stmtDeleteReponses->execute();

    // Supprimer le sujet
    $sqlDeleteSujet = "DELETE FROM sujets_forum WHERE id = :idSujet";
    $stmtDeleteSujet = $dbh->prepare($sqlDeleteSujet);
    $stmtDeleteSujet->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
    $stmtDeleteSujet->execute();

    // Rediriger l'utilisateur vers la page d'accueil du forum après la suppression
    header("Location: accueilForum.php");
    exit;
} else {
    // Rediriger l'utilisateur vers la page d'accueil du forum si l'ID du sujet n'est pas valide
    header("Location: accueilForum.php?erreur=2");
    exit;
}