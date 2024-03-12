<?php
try {
    // Inclusion du fichier de connexion à la base de données
    global $dbh;
    include('../../php/connect.php');

    // Vérification de l'existence et de la validité de l'ID du sujet
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $idSujet = $_GET['id'];
        $sql = "SELECT sujets_forum.*, utilisateurs.nom_utilisateur AS nom_auteur
                FROM sujets_forum
                LEFT JOIN utilisateurs ON sujets_forum.id_utilisateur = utilisateurs.id_utilisateur
                WHERE id_sujet = :id";
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $idSujet, PDO::PARAM_INT);
        $stmt->execute();

        // Vérification de l'existence du sujet
        if ($stmt->rowCount() > 0) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title><?= $row['titre'] ?></title>
                <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
            </head>
            <body>
            <div class="container mt-5">
                <h1><?= $row['titre'] ?></h1>
                <p><strong>Auteur :</strong> <?= $row['nom_auteur'] ?> | <strong>Date de création :</strong> <?= $row['date_creation'] ?></p>
                <p><?= $row['contenu'] ?></p>
                <h2>Réponses au sujet</h2>
                <?php
                // Récupération et affichage des réponses pour ce sujet
                $sqlReponses = "SELECT reponses_forum.*, utilisateurs.nom_utilisateur AS nom_auteur_reponse
                                FROM reponses_forum
                                LEFT JOIN utilisateurs ON reponses_forum.id_utilisateur = utilisateurs.id_utilisateur
                                WHERE id_sujet = :id
                                ORDER BY reponses_forum.date_creation DESC";
                $stmtReponses = $dbh->prepare($sqlReponses);
                $stmtReponses->bindParam(':id', $idSujet, PDO::PARAM_INT);
                $stmtReponses->execute();

                if ($stmtReponses->rowCount() > 0) {
                    while($rowReponse = $stmtReponses->fetch(PDO::FETCH_ASSOC)) {
                        echo '<div class="card mb-3"><div class="card-body">';
                        echo '<h5 class="card-title">'.$rowReponse['nom_auteur_reponse'].' - '.$rowReponse['date_creation'].'</h5>';
                        echo '<p class="card-text">'.$rowReponse['contenu'].'</p>';
                        if ($rowReponse['id_utilisateur'] === $row['id_utilisateur']) {
                            echo '<p class="text-muted">Auteur</p>';
                        } else {
                            echo '<a href="#" class="btn btn-danger btn-sm">Signaler ce message</a>';
                        }
                        echo '</div></div>';
                    }
                } else {
                    echo "<p>Aucune réponse trouvée pour ce sujet.</p>";
                }
                ?>
            </div>
            </body>
            </html>
            <?php
        } else {
            echo "<p>Aucun sujet trouvé avec cet identifiant.</p>";
        }
    } else {
        echo "<p>Identifiant de sujet invalide.</p>";
    }
} catch (PDOException $e) {
    // Gestion des erreurs PDO
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>