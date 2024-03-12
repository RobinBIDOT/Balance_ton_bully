<?php
try {
    // Inclusion du fichier de connexion à la base de données
    global $dbh;
    include('../../php/connect.php');

    // Définition du nombre d'éléments par page
    $elementsParPage = 8;

    // Récupération du numéro de page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Calcul du décalage
    $offset = ($page - 1) * $elementsParPage;

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

            // Comptage du nombre total de réponses
            $sqlCount = "SELECT COUNT(*) AS total FROM reponses_forum WHERE id_sujet = :id";
            $stmtCount = $dbh->prepare($sqlCount);
            $stmtCount->bindParam(':id', $idSujet, PDO::PARAM_INT);
            $stmtCount->execute();
            $totalReponses = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            // Calcul du nombre total de pages
            $totalPages = ceil($totalReponses / $elementsParPage);
            ?>
            <!DOCTYPE html>
            <html lang="fr">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Titre de votre sujet</title>
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
                <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                <link rel="stylesheet" href="../../css/style.css">
            </head>
            <body>
            <div class="container mt-5 mx-auto max-w-3xl">
                <h1 class="text-3xl font-bold mb-4"><?= $row['titre'] ?></h1>
                <p class="mb-2"><strong>Auteur :</strong> <?= $row['nom_auteur'] ?> | <strong>Date de création :</strong> <?= $row['date_creation'] ?></p>
                <p class="mb-8"><?= $row['contenu'] ?></p>
                <h2 class="text-2xl font-bold mb-4">Réponses au sujet</h2>
                <div class="transition"></div>
                <?php
                // Récupération et affichage des réponses pour ce sujet
                echo '<div style="background-color: #0854C7;" class="p-4 rounded-md mb-4">';
                $sqlReponses = "SELECT reponses_forum.*, utilisateurs.nom_utilisateur AS nom_auteur_reponse
                                FROM reponses_forum
                                LEFT JOIN utilisateurs ON reponses_forum.id_utilisateur = utilisateurs.id_utilisateur
                                WHERE id_sujet = :id
                                ORDER BY reponses_forum.date_creation DESC
                                LIMIT :offset, :elementsParPage";
                $stmtReponses = $dbh->prepare($sqlReponses);
                $stmtReponses->bindParam(':id', $idSujet, PDO::PARAM_INT);
                $stmtReponses->bindParam(':offset', $offset, PDO::PARAM_INT);
                $stmtReponses->bindParam(':elementsParPage', $elementsParPage, PDO::PARAM_INT);
                $stmtReponses->execute();

                if ($stmtReponses->rowCount() > 0) {
                    $reponses = $stmtReponses->fetchAll(PDO::FETCH_ASSOC);

                    foreach($reponses as $rowReponse) {
                        echo '<div class="bg-white shadow-md rounded-md p-4 mb-4">';
                        echo '<h5 class="text-lg font-semibold mb-2">'.$rowReponse['nom_auteur_reponse'].' - '.$rowReponse['date_creation'].'</h5>';
                        echo '<p class="mb-2">'.$rowReponse['contenu'].'</p>';
                        echo '<div class="flex justify-end">';
                        if ($rowReponse['id_utilisateur'] === $row['id_utilisateur']) {
                            echo '<p class="text-gray-500">Auteur</p>';
                        } else {
                            echo '<a href="#" class="inline-block bg-red-500 text-white px-2 py-1 rounded-md">Signaler</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                    }

                    // Affichage de la pagination
                    if ($totalPages > 1) {
                        echo '<nav aria-label="Pagination">';
                        echo '<ul class="pagination justify-content-center">';
                        echo '<li class="page-item '.($page <= 1 ? 'disabled' : '').'">';
                        echo '<a class="page-link" href="?id='.$idSujet.'&page='.($page - 1).'" tabindex="-1">Précédent</a>';
                        echo '</li>';
                        for ($i = 1; $i <= $totalPages; $i++) {
                            echo '<li class="page-item '.($page == $i ? 'active' : '').'">';
                            echo '<a class="page-link" href="?id='.$idSujet.'&page='.$i.'">'.$i.'</a>';
                            echo '</li>';
                        }
                        echo '<li class="page-item '.($page >= $totalPages ? 'disabled' : '').'">';
                        echo '<a class="page-link" href="?id='.$idSujet.'&page='.($page + 1).'">Suivant</a>';
                        echo '</li>';
                        echo '</ul>';
                        echo '</nav>';
                    }
                } else {
                    echo "<p class='text-gray-500'>Aucune réponse trouvée pour ce sujet.</p>";
                }
                echo '</div>'; // Fermeture de la div de fond
                ?>
                <div class="transition"></div>

                <!-- Formulaire d'ajout de réponse -->
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <div class="mt-5">
                        <h2 class="text-2xl font-bold mb-4">Ajouter une réponse</h2>
                        <form action="ajouterReponse.php" method="post">
                            <div class="form-group">
                                <label for="contenuReponse">Contenu de la réponse :</label>
                                <textarea class="form-control" id="contenuReponse" name="contenuReponse" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="idSujet" value="<?= $idSujet ?>">
                            <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
                        </form>
                    </div>
                <?php else : ?>
                    <p class="text-gray-500 mt-5">Connectez-vous pour pouvoir ajouter une réponse.</p>
                <?php endif; ?>
            </div>
            </body>
            </html>
            <?php
        } else {
            echo "<p class='text-gray-500'>Aucun sujet trouvé avec cet identifiant.</p>";
        }
    } else {
        echo "<p class='text-gray-500'>Identifiant de sujet invalide.</p>";
    }
}catch (PDOException $e) {
    // Gestion des erreurs PDO
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?>