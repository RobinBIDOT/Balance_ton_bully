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
include('../php/tools/functions.php');


// Vérifier si le formulaire a été soumis et si l'ID du sujet est valide
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_POST){
        try {
            $idSujet = $_POST['id_sujet'];
            $dbh = dbConnexion();
            $sqlDeleteReponses = "DELETE FROM reponses_forum WHERE id_sujet = ?";
            $stmtDeleteReponses = $dbh->prepare($sqlDeleteReponses);
            $stmtDeleteReponses->execute([$idSujet]);

            // Supprimer le sujet
            $sqlDeleteSujet = "DELETE FROM sujets_forum WHERE id = :idSujet";
            $stmtDeleteSujet = $dbh->prepare($sqlDeleteSujet);
            $stmtDeleteSujet->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
            $stmtDeleteSujet->execute();

            header('Location: ../pages/accueilForum.php');
        }catch (PDOException $e){
            echo json_encode([
                'status' => 'error',
                'message' => 'Veuillez reessayer plus tard'
            ]);
        }

    }
}else{
    echo 'raté';
}