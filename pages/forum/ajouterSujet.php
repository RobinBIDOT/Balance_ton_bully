<?php
// Démarrage de la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: ../../php/connexion.php");
    exit(); // Arrêter le script
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Inclusion du fichier de connexion à la base de données
        include('../../php/tools/functions.php');
        $dbh = dbConnexion();

        // Récupérer les données du formulaire
        $titre = $_POST['titre'];
        $contenu = $_POST['contenu'];
        $idUtilisateur = $_SESSION['user_id']; // ID de l'utilisateur connecté

        // Préparer la requête d'insertion
        $sql = "INSERT INTO sujets_forum (titre, contenu, id_utilisateur, date_creation)
                VALUES (:titre, :contenu, :idUtilisateur, NOW())";
        $stmt = $dbh->prepare($sql);

        // Liaison des paramètres
        $stmt->bindParam(':titre', $titre, PDO::PARAM_STR);
        $stmt->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $stmt->bindParam(':idUtilisateur', $idUtilisateur, PDO::PARAM_INT);

        // Exécuter la requête
        $stmt->execute();

        // Redirection vers la page d'accueil du forum après l'ajout du sujet
        header("Location: accueilForum.php");
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