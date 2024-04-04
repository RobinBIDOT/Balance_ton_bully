<?php
/**
 * Ce script est utilisé pour mettre à jour une actualité spécifiée par son identifiant.
 *
 * Le script reçoit les données de l'actualité (titre, contenu, lien de l'article, et éventuellement une nouvelle photo)
 * via une requête POST. Il gère l'upload de la nouvelle photo si elle est fournie, puis met à jour l'actualité
 * dans la base de données avec les nouvelles informations.
 *
 * Si la mise à jour réussit, le script renvoie un succès. En cas d'échec, il renvoie un échec.
 *
 * @package    balance_ton_bully
 * @subpackage admin
 */
session_start();
// Connection à la base de données et mise à jour l'actualité
include('../php/tools/functions.php');
$dbh = dbConnexion();

// Vérifier que toutes les données requises sont présentes
if (isset($_POST['id'], $_POST['titre'], $_POST['contenu'], $_POST['lien_article'])) {
    $id = $_POST['id'];
    $titre = $_POST['titre'];
    $contenu = $_POST['contenu'];
    $lien_article = $_POST['lien_article'];
    $photoChanged = isset($_POST['photoChanged']) && $_POST['photoChanged'] == 'yes';

    // Récupérer le chemin actuel de la photo
    $stmt = $dbh->prepare("SELECT photo FROM actualites WHERE id_actualite = ?");
    $stmt->execute([$id]);
    $actualite = $stmt->fetch(PDO::FETCH_ASSOC);
    $photoPath = $actualite['photo'];

    // Gérer l'upload de la photo si elle est présente
    if ($photoChanged && isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        // Vérifier le type et la taille de la photo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($_FILES['photo']['type'], $allowedTypes) && $_FILES['photo']['size'] <= 5000000) {
            $extension = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $photoPath = '/Balance_ton_bully/assets/' . uniqid() . '.' . $extension;

            // Déplacer le fichier uploadé vers le nouveau chemin
            if (!move_uploaded_file($_FILES['photo']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $photoPath)) {
                echo json_encode(['success' => false, 'error' => 'Erreur de téléchargement de la photo']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Type de fichier non autorisé ou taille trop grande']);
            exit;
        }
    } else {
        $photoPath = $actualite['photo']; // Conserver la photo existante
    }

    // Préparation de la requête SQL pour la mise à jour
    $sql = "UPDATE actualites SET titre = ?, contenu = ?, lien_article = ?, photo = ? WHERE id_actualite = ?";
    $stmt = $dbh->prepare($sql);
    $success = $stmt->execute([$titre, $contenu, $lien_article, $photoPath, $id]);

    if ($success) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Mise à jour échouée']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Données manquantes pour la mise à jour']);
}