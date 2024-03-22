<?php
/**
 * Script de suppression d'un sujet de forum et de ses réponses.
 *
 * Ce script permet de supprimer un sujet spécifié ainsi que toutes ses réponses
 * associées de la base de données. Il est principalement destiné à être utilisé
 * par les administrateurs du forum.
 *
 * @package balance_ton_bully
 * @subpackage forum
 */

session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nickName'])) {
    // Rediriger l'utilisateur vers la page de connexion s'il n'est pas connecté
    header("Location: ../php/connexion.php");
    exit;
}

// Vérifier si le formulaire a été soumis et si l'ID du sujet est valide
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