<?php
include '../php/tools/functions.php';
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    if ($_POST){
        try {
            $dbConnect = dbConnexion();
            $stmt = $dbConnect ->prepare('UPDATE utilisateurs SET name = ?, firstName = ?, userName = ?, mail = ? WHERE id = ?');
            $stmt->execute([$_POST['name'], $_POST['firstName'], $_POST['userName'], $_POST['mail'], $_SESSION['id']]);
            echo json_encode([
                'status' => 'success',
                'message' => 'Votre profil a été mis à jour'
            ]);
        }catch(PDOException $e){
            echo json_encode([
                'status' => 'error',
                'message' => 'Veuillez reessayer plus tard'
            ]);
        }
    }
}