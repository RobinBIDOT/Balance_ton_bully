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

include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['id'], $_POST['firstName'], $_POST['name'], $_POST['userName'], $_POST['mail'])) {
    $id = $_POST['id'];
    $firstName = $_POST['firstName'];
    $name = $_POST['name'];
    $userName = $_POST['userName'];
    $mail = $_POST['mail'];

    if (isset($_FILES['photo_avatar']) && $_FILES['photo_avatar']['error'] == 0) {
        // Vérifier le type et la taille de la photo
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (in_array($_FILES['photo_avatar']['type'], $allowedTypes) && $_FILES['photo_avatar']['size'] <= 5000000) {
            $extension = pathinfo($_FILES['photo_avatar']['name'], PATHINFO_EXTENSION);
            $photoPath = '/Balance_ton_bully/assets/' . uniqid() . '.' . $extension;

            // Déplacer le fichier uploadé vers le nouveau chemin
            if (!move_uploaded_file($_FILES['photo_avatar']['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . $photoPath)) {
                echo json_encode(['success' => false, 'error' => 'Erreur de téléchargement de la photo']);
                exit;
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Type de fichier non autorisé ou taille trop grande']);
            exit;
        }
    } else {
        $photoPath = '/Balance_ton_bully/assets/avatarProfil.png'; // Chemin par défaut si aucune nouvelle photo n'est fournie
    }

    $sql = "UPDATE utilisateurs SET firstName = ?, name = ?, userName = ?, mail = ?, photo_avatar = ? WHERE id = ?";
    $stmt = $dbh->prepare($sql);
    $stmt->execute([$firstName, $name, $userName, $mail, $photoPath, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Aucune modification effectuée']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Données manquantes']);
}
?>