<?php
/**
 * Script pour la modification d'une réponse dans un forum.
 *
 * Ce script permet à un utilisateur connecté de modifier une réponse qu'il a postée dans un forum.
 * Il vérifie si l'utilisateur est l'auteur de la réponse et permet la modification si c'est le cas.
 * En cas de réussite, l'utilisateur est redirigé vers le sujet avec un message de succès.
 * Sinon, il est redirigé avec un message d'erreur approprié.
 *
 * @package balance_ton_bully
 * @subpackage forum
 *
 * @param int $_POST['idReponse'] - L'identifiant de la réponse à modifier.
 * @param string $_POST['contenuReponse'] - Le nouveau contenu de la réponse.
 * @return void
 */

include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: ../php/connexion.php");
    exit();
}

// Vérification de la méthode de requête
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérification de la présence des données POST
    if (isset($_POST['idReponse'], $_POST['contenuReponse'])) {
        $idReponse = $_POST['idReponse'];
        $nouveauContenu = $_POST['contenuReponse'];

        try {
            // Récupération de l'identifiant de l'utilisateur
            $idUtilisateur = $_SESSION['id'];

            // Récupération de l'ID du sujet depuis la base de données
            $sqlIdSujet = "SELECT id_sujet FROM reponses_forum WHERE id_reponse = :idReponse";
            $stmtIdSujet = $dbh->prepare($sqlIdSujet);
            $stmtIdSujet->bindParam(':idReponse', $idReponse, PDO::PARAM_INT);
            $stmtIdSujet->execute();

            if ($stmtIdSujet->rowCount() > 0) {
                $rowIdSujet = $stmtIdSujet->fetch(PDO::FETCH_ASSOC);
                $idSujet = $rowIdSujet['id_sujet'];

                // Vérification si l'utilisateur est l'auteur de la réponse
                $sqlAuteur = "SELECT id_utilisateur FROM reponses_forum WHERE id_reponse = :idReponse";
                $stmtAuteur = $dbh->prepare($sqlAuteur);
                $stmtAuteur->bindParam(':idReponse', $idReponse, PDO::PARAM_INT);
                $stmtAuteur->execute();

                if ($stmtAuteur->rowCount() > 0) {
                    $rowAuteur = $stmtAuteur->fetch(PDO::FETCH_ASSOC);
                    if ($idUtilisateur == $rowAuteur['id_utilisateur']) {
                        // Mise à jour du contenu de la réponse
                        $sqlUpdate = "UPDATE reponses_forum SET contenu = :nouveauContenu WHERE id_reponse = :idReponse";
                        $stmtUpdate = $dbh->prepare($sqlUpdate);
                        $stmtUpdate->bindParam(':nouveauContenu', $nouveauContenu, PDO::PARAM_STR);
                        $stmtUpdate->bindParam(':idReponse', $idReponse, PDO::PARAM_INT);
                        $stmtUpdate->execute();

                        // Redirection vers la page du sujet avec un message de succès
                        header("Location: ../pages/sujet.php?id=$idSujet&success=1");
                        exit();
                    } else {
                        header("Location: ../pages/sujet.php?id=$idSujet&error=3");
                        exit();
                    }
                } else {
                    header("Location: ../pages/sujet.php?id=$idSujet&error=4");
                    exit();
                }
            } else {
                header("Location: ../pages/accueilForum.php"); // Redirection vers la page d'accueil
                exit();
            }
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        header("Location: ../pages/accueilForum.php"); // Redirection vers la page d'accueil
        exit();
    }
} else {
    header("Location: ../pages/accueilForum.php"); // Redirection vers la page d'accueil
    exit();
}