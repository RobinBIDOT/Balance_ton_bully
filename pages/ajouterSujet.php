<?php
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
 * @param PDO $dbh Connexion à la base de données.
 * @param string $titre Titre du sujet.
 * @param string $contenu Contenu du sujet.
 * @param int $idUtilisateur Identifiant de l'utilisateur.
 * @return void
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

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $idUtilisateur = $_SESSION['id'];

        insererSujet($dbh, $titre, $contenu, $idUtilisateur);

        header("Location: ../pages/accueilForum.php");
        exit();
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} else {
    header("Location: ../pages/accueilForum.php");
    exit();
}
?>
