<?php
// Déclaration des variables globales pour les informations de connexion
global $port, $pseudo, $password;

// Inclusion du fichier de configuration contenant les informations de connexion
include('config.php');

// Création d'une instance PDO pour établir la connexion à la base de données
$dsn = "mysql:host=localhost:$port;dbname=balance_ton_bully"; // Chaîne de connexion DSN

try {
    // Création de l'objet PDO avec les informations de connexion
    $dbh = new PDO($dsn, $pseudo, $password);

    // Configuration des options de PDO pour gérer les erreurs
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Gestion des exceptions en cas d'échec de la connexion
    echo "Erreur de connexion : " . $e->getMessage();
    exit(); // Arrêt du script en cas d'erreur
}
?>