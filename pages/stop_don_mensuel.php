<?php
include('../php/tools/functions.php');
$dbh = dbConnexion();

if (isset($_POST['donId'])) {
    $donId = $_POST['donId'];
    try {
        $stmt = $dbh->prepare("UPDATE Dons SET stopper_don_mensuel = TRUE, date_arret_don_mensuel = CURDATE() WHERE id = ?");
        $stmt->execute([$donId]);
        echo json_encode(['success' => true]);
    } catch (PDOException $e) {
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No donId provided']);
}