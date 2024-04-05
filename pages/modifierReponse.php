<?php
/**
 * Script de modification d'une réponse dans un forum.
 *
 * Ce script permet à l'utilisateur de modifier le contenu de sa réponse dans un sujet du forum.
 * L'utilisateur doit être connecté et être l'auteur de la réponse pour pouvoir la modifier.
 * Si les conditions sont remplies, un formulaire permettant de modifier la réponse est affiché.
 * Après soumission du formulaire, la réponse est mise à jour dans la base de données et
 * l'utilisateur est redirigé vers la page du sujet.
 *
 * @package balance_ton_bully
 * @subpackage forum
 *
 * @param int $_GET['id'] - L'identifiant de la réponse à modifier.
 * @param int $_GET['idSujet'] - L'identifiant du sujet associé à la réponse.
 * @return void
 */

// Vérifier si l'utilisateur est connecté
session_start();
if (!isset($_SESSION['nickName'])) {
    header("Location: ../php/connexion.php");
    exit;
}

// Vérifier si l'ID de la réponse et l'ID du sujet sont définis dans l'URL
if (isset($_GET['id']) && isset($_GET['idSujet'])) {
    $idReponse = $_GET['id'];
    $idSujet = $_GET['idSujet'];

    // Inclure le fichier de connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();

    // Vérifier si l'utilisateur est l'auteur de la réponse
    $sql = "SELECT * FROM reponses_forum WHERE id_reponse = :idReponse AND id_utilisateur = :idUtilisateur";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':idReponse', $idReponse, PDO::PARAM_INT);
    $stmt->bindParam(':idUtilisateur', $_SESSION['id'], PDO::PARAM_INT);
    $stmt->execute();

    // Vérifier si la réponse existe et si l'utilisateur est l'auteur
    if ($stmt->rowCount() > 0) {
        // Récupérer les données de la réponse
        $reponse = $stmt->fetch(PDO::FETCH_ASSOC);
        $contenuReponse = $reponse['contenu'];

        // Vérifier si le formulaire de modification a été soumis
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Récupérer le nouveau contenu de la réponse depuis le formulaire
            $nouveauContenu = $_POST['nouveauContenu'];

            // Mettre à jour le contenu de la réponse dans la base de données
            $sqlUpdate = "UPDATE reponses_forum SET contenu = :contenu WHERE id_reponse = :idReponse";
            $stmtUpdate = $dbh->prepare($sqlUpdate);
            $stmtUpdate->bindParam(':contenu', $nouveauContenu, PDO::PARAM_STR);
            $stmtUpdate->bindParam(':idReponse', $idReponse, PDO::PARAM_INT);
            $stmtUpdate->execute();

            // Rediriger l'utilisateur vers la page du sujet après la modification
            header("Location: sujet.php?id=$idSujet");
            exit;
        }
    } else {
        // Rediriger l'utilisateur vers la page du sujet avec un message d'erreur si la réponse n'existe pas ou s'il n'est pas l'auteur
        header("Location: sujet.php?id=$idSujet&erreur=1");
        exit;
    }

//else {
//    // Rediriger l'utilisateur vers la page du sujet si les paramètres requis ne sont pas fournis dans l'URL
//    header("Location: sujet.php?id=$idSujet&erreur=2");
//    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Réponse</title>
    <?php include('../includes/headLink.php') ?>
</head>
<?php include('../includes/headerNav.php')?>
<body>
<div class="container mt-5">
    <h2 class="mb-4">Modifier la Réponse</h2>
    <form action="" method="post">
        <div class="form-group">
            <label for="nouveauContenu">Nouveau Contenu de la Réponse :</label>
            <textarea class="form-control" id="nouveauContenu" name="nouveauContenu" rows="4" required><?= $contenuReponse ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer les Modifications</button>
    </form>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>
