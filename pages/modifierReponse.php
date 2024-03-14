<?php
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Redirection si l'utilisateur n'est pas connecté
if (!isset($_SESSION['pseudo'])) {
    header("Location: ../php/connexion.php");
    exit();
}

// Vérification de la présence des paramètres d'URL
if (!isset($_GET['id']) || !isset($_GET['idSujet']) || !is_numeric($_GET['id']) || !is_numeric($_GET['idSujet'])) {
    header("Location: ../pages/accueilForum.php");
    exit();
}

$idReponse = $_GET['id'];
$idSujet = $_GET['idSujet'];

try {
    // Récupération de la réponse
    $sql = "SELECT id_utilisateur, contenu FROM reponses_forum WHERE id_reponse = :id";
    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':id', $idReponse, PDO::PARAM_INT);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $idUtilisateur = $row['id_utilisateur'];
        $contenuReponse = $row['contenu'];

        // Vérification si l'utilisateur connecté est l'auteur de la réponse
        if ($_SESSION['id'] === $idUtilisateur) {
            // Afficher le formulaire de modification de réponse
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Modifier la réponse</title>
                <!-- Inclure les CDN pour Bootstrap -->
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
            </head>
            <body>
            <div class="container mt-5">
                <h2>Modifier la réponse</h2>
                <form action="modifierReponseTraitement.php" method="post">
                    <input type="hidden" name="idReponse" value="<?= $idReponse ?>">
                    <input type="hidden" name="idSujet" value="<?= $idSujet ?>">
                    <div class="mb-3">
                        <label for="contenuReponse" class="form-label">Contenu de la réponse :</label>
                        <textarea class="form-control" id="contenuReponse" name="contenuReponse" rows="4" required><?= $contenuReponse ?></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                </form>
            </div>
            </body>
            </html>
            <?php
        } else {
            header("Location: ../pages/sujet.php?id=" . $idSujet . "&error=1");
            exit();
        }
    } else {
        header("Location: ../pages/sujet.php?id=" . $idSujet . "&error=2");
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
}
?>
