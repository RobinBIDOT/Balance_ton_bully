<?php
/**
 * Script pour la mise à jour des informations d'un utilisateur spécifié.
 *
 * Ce script reçoit les données de l'utilisateur (prénom, nom, pseudo, email et éventuellement une nouvelle photo)
 * via une requête POST. Il gère l'upload de la nouvelle photo si elle est fournie, puis met à jour l'utilisateur
 * dans la base de données avec les nouvelles informations.
 *
 * @package balance_ton_bully
 * @subpackage admin
 */
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions de connexion à la base de données
include('../php/tools/functions.php');
// Connexion à la base de données
$dbh = dbConnexion();

// Vérification de la méthode de requête
if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    // Vérification des données POST
    if (isset($_POST['id'], $_POST['firstName'], $_POST['name'], $_POST['userName'], $_POST['mail'])) {
        // Récupération des valeurs POST
        $id = $_POST['id'];
        $firstName = $_POST['firstName'];
        $name = $_POST['name'];
        $userName = $_POST['userName'];
        $mail = $_POST['mail'];
        error_log("ID reçu : " . $id);
        // Initialisation du chemin de la photo
        $photoPath = '';

        // Récupération du chemin de la photo actuelle depuis la base de données
        $currentPhotoStmt = $dbh->prepare("SELECT photo_avatar FROM utilisateurs WHERE id = ?");
        $currentPhotoStmt->execute([$id]);
        $currentPhoto = $currentPhotoStmt->fetchColumn();

        // Gestion de l'upload de la nouvelle photo, si présente
        if (isset($_FILES['photo_avatar']) && $_FILES['photo_avatar']['error'] == 0) {
            // Vérification du type et de la taille du fichier
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array($_FILES['photo_avatar']['type'], $allowedTypes) && $_FILES['photo_avatar']['size'] <= 5000000) {
                $extension = pathinfo($_FILES['photo_avatar']['name'], PATHINFO_EXTENSION);
                $photoPath = '/Balance_ton_bully/assets/' . uniqid() . '.' . $extension;

                // Déplacement du fichier téléchargé
                if (move_uploaded_file($_FILES['photo_avatar']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $photoPath)) {
                    // Succès du téléchargement
                    // (Optionnel) Supprimer l'ancienne photo si nécessaire
                } else {
                    // Échec du téléchargement
                    echo json_encode(['success' => false, 'error' => 'Erreur de téléchargement de la photo']);
                    exit;
                }
            } else {
                // Type de fichier non autorisé ou taille trop grande
                echo json_encode(['success' => false, 'error' => 'Type de fichier non autorisé ou taille trop grande']);
                exit;
            }
        } else {
            // Aucune nouvelle photo n'est fournie, utiliser la photo actuelle
            $photoPath = $currentPhoto;
        }

        // Préparation de la requête de mise à jour
        $updateSql = "UPDATE utilisateurs SET firstName = ?, name = ?, userName = ?, mail = ?, photo_avatar = ? WHERE id = ?";
        $updateStmt = $dbh->prepare($updateSql);

        // Exécution de la requête avec les paramètres
        if (!$updateStmt->execute([$firstName, $name, $userName, $mail, $photoPath, $id])) {
            // Gestion de l'erreur
            error_log("Erreur SQL lors de la mise à jour de l'utilisateur : " . implode(";", $updateStmt->errorInfo()));
            echo json_encode(['success' => false, 'error' => 'Erreur lors de la mise à jour']);
            exit;
        }

        if ($updateStmt->rowCount() > 0) {
            // Succès de la mise à jour
            echo json_encode(['success' => true]);
        } else {
            // Aucune modification effectuée ou données identiques
            echo json_encode(['success' => false, 'error' => 'Aucune modification effectuée ou données identiques']);
        }
    } else {
        // Données POST manquantes
        echo json_encode(['success' => false, 'error' => 'Données manquantes']);
    }
} else {
    // Méthode de requête invalide
    echo json_encode(['success' => false, 'error' => 'Méthode de requête invalide']);
    error_log("Erreur : Méthode de requête invalide");
}