<?php
/**
 * Script pour ajouter une réponse à un sujet sur un forum.
 * L'utilisateur doit être connecté pour pouvoir poster une réponse.
 */

include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['nickName'])) {
    header("Location: ../pages/connexion.php");
    exit();
}

var_dump($_POST['contenuReponse']);
var_dump($_POST['idSujet']);

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['contenuReponse']) && isset($_POST['idSujet'])) {
        $idSujet = $_POST['idSujet'];
        $contenu = $_POST['contenuReponse'];
        var_dump($idSujet);
        try {
            ajouterReponse($dbh, $idSujet, $contenu, $_SESSION['nickName']);
            // Rediriger vers la page du sujet après l'ajout de la réponse

            header("Location: ../pages/sujet.php?id=" . $idSujet);
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        //Si les données du formulaire sont manquantes, rediriger vers la page d'accueil
        //header("Location: accueilForum.php");
        exit();
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page d'accueil
    header("Location: ../pages/accueilForum.php");
    exit();
}

/**
 * Ajoute une réponse dans la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $idSujet ID du sujet auquel la réponse est associée.
 * @param string $contenu Contenu de la réponse.
 * @param string $pseudo Pseudo de l'utilisateur qui poste la réponse.
 * @throws PDOException Si une erreur survient lors de l'exécution de la requête.
 */
function ajouterReponse($dbh, $idSujet, $contenu, $pseudo) {
    // Récupérer l'ID de l'utilisateur à partir de son pseudo
    $sqlUserId = "SELECT id FROM utilisateurs WHERE userName = :pseudo";
    $stmtUserId = $dbh->prepare($sqlUserId);
    $stmtUserId->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmtUserId->execute();
    $userId = $stmtUserId->fetchColumn();

    // Insérer la réponse dans la base de données
    $sql = "INSERT INTO reponses_forum (id_sujet, contenu, id_utilisateur, date_creation)
            VALUES (:idSujet, :contenu, :userId, NOW())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
    $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
    $stmt->bindParam(':userId', $userId, PDO::PARAM_INT);
    $stmt->execute();
}
?>