<?php
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: ../php/connexion.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['contenuReponse']) && isset($_POST['idSujet'])) {
        $idSujet = $_POST['idSujet'];
        $contenu = $_POST['contenuReponse'];

        try {
            ajouterReponse($dbh, $idSujet, $contenu, $_SESSION['pseudo']);
            // Rediriger vers la page du sujet après l'ajout de la réponse
            header("Location: sujet.php?id=" . $idSujet);
            exit();
        } catch (PDOException $e) {
            echo "Erreur : " . $e->getMessage();
        }
    } else {
        // Si les données du formulaire sont manquantes, rediriger vers la page d'accueil
        header("Location: accueilForum.php");
        exit();
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page d'accueil
    header("Location: accueilForum.php");
    exit();
}

/**
 * Ajoute une réponse dans la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $idSujet ID du sujet auquel la réponse est associée.
 * @param string $contenu Contenu de la réponse.
 * @param string $pseudo Pseudo de l'utilisateur qui poste la réponse.
 */
function ajouterReponse($dbh, $idSujet, $contenu, $pseudo) {
    // Récupérer l'ID de l'utilisateur à partir de son pseudo
    $sqlUserId = "SELECT id_utilisateur FROM utilisateurs WHERE pseudo = :pseudo";
    $stmtUserId = $dbh->prepare($sqlUserId);
    $stmtUserId->bindParam(':pseudo', $pseudo, PDO::PARAM_STR);
    $stmtUserId->execute();
    $userId = $stmtUserId->fetch(PDO::FETCH_ASSOC)['id_utilisateur'];

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