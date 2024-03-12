<?php
global $dbh;
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit(); // Arrêter le script
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Inclure le fichier de connexion à la base de données
    include('../../php/connect.php');

    // Récupérer les données du formulaire
    $idSujet = $_POST['id_sujet'];
    $contenu = $_POST['contenu'];
    $idUtilisateur = $_SESSION['user_id']; // ID de l'utilisateur connecté

    try {
        // Préparer la requête d'insertion
        $sql = "INSERT INTO reponses_forum (id_sujet, contenu, id_utilisateur, date_creation)
                VALUES (:idSujet, :contenu, :idUtilisateur, NOW())";
        $stmt = $dbh->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':idSujet', $idSujet, PDO::PARAM_INT);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();

        // Redirection vers la page du sujet après l'ajout de la réponse
        header("Location: sujet.php?id=" . $idSujet);
        exit();
    } catch (PDOException $e) {
        // Gestion des erreurs PDO
        echo "Erreur de connexion à la base de données : " . $e->getMessage();
    }
} else {
    // Rediriger vers la page d'accueil si le formulaire n'a pas été soumis
    header("Location: accueilForum.php");
    exit();
}
?>