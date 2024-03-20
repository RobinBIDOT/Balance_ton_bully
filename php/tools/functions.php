<?php
/**
 * Établit une connexion à la base de données.
 *
 * Cette fonction lit les paramètres de connexion à partir d'un fichier JSON,
 * puis tente d'établir une connexion à la base de données avec PDO.
 * Elle active le mode d'erreur PDO::ERRMODE_EXCEPTION pour une meilleure gestion des erreurs.
 *
 * @return PDO Objet PDO pour interagir avec la base de données.
 */
function dbConnexion(): PDO {
    // Chemin vers le fichier JSON contenant les informations de connexion
    $file_path = '../php/tools/info.json';

    // Lecture des informations de connexion depuis le fichier JSON
    $tool = json_decode(file_get_contents($file_path), true);

    // Construction du Data Source Name (DSN)
    $dsn = "mysql:host=" . $tool['host'] . ";dbname=". $tool['database'] . ";port=". $tool['port'] . ";charset=". $tool['charset'];

    // Récupération des informations d'authentification
    $user = $tool['user'];
    $password = $tool['pwd'];

    try {
        // Tentative de connexion à la base de données avec les paramètres fournis
        $dbh = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    } catch (Exception $e) {
        // Gestion des erreurs de connexion
        $message = $e->getMessage();
        echo "Erreur de connexion à la base de données : " . $message;
        exit();
    }

    // Retourne l'objet PDO pour les interactions ultérieures avec la base de données
    return $dbh;
}