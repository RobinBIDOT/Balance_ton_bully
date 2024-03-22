<?php
/**
 * Script pour insérer un nouveau sujet sur un forum.
 * L'utilisateur doit être connecté pour pouvoir créer un sujet.
 */

include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['nickName'])) {
    header("Location: ../php/connexion.php");
    exit();
}

/**
 * Insère un nouveau sujet dans la base de données.
 *
 * Cette fonction prend les informations du sujet et l'identifiant de l'utilisateur,
 * puis insère le sujet dans la base de données. Elle utilise une requête préparée
 * pour prévenir les injections SQL.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param string $titre Titre du sujet.
 * @param string $contenu Contenu du sujet.
 * @param int $idUtilisateur Identifiant de l'utilisateur.
 * @throws PDOException Si une erreur survient lors de l'exécution de la requête.
 */
function insererSujet($dbh, $titre, $contenu, $idUtilisateur) {
    $sql = "INSERT INTO sujets_forum (titre, contenu, id_utilisateur, date_creation)
            VALUES (:titre, :contenu, :idUtilisateur, NOW())";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
    $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
    $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);
    $stmt->execute();
}

// Traitement du formulaire de création de sujet
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $idUtilisateur = $_SESSION['id'];

        insererSujet($dbh, $titre, $contenu, $idUtilisateur);

        // Rediriger vers la page d'accueil du forum après l'ajout du sujet
        header("Location: ../pages/accueilForum.php");
        exit();
    } catch (PDOException $e) {
        // Gestion de l'erreur et affichage d'un message d'erreur
        echo "Erreur : " . $e->getMessage();
    }
} else {
    // Si le formulaire n'a pas été soumis, rediriger vers la page d'accueil du forum
    header("Location: ../pages/accueilForum.php");
    exit();
}
?>
