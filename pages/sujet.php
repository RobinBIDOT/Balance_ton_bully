<?php
try {
    // Inclusion du fichier de connexion à la base de données
    include('../php/tools/functions.php');
    $dbh = dbConnexion();
    session_start();

    // Définition du nombre d'éléments par page
    $elementsParPage = 8;

    // Récupération du numéro de page
    $page = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Calcul du décalage
    $offset = ($page - 1) * $elementsParPage;

    // Vérification de l'existence et de la validité de l'ID du sujet
    if(isset($_GET['id']) && is_numeric($_GET['id'])) {
        $idSujet = $_GET['id'];
        $sql = "SELECT sujets_forum.*, utilisateurs.userName AS nom_auteur
                FROM sujets_forum
                LEFT JOIN utilisateurs ON sujets_forum.id_utilisateur = utilisateurs.id
                WHERE sujets_forum.id = :id";
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
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
                <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
                <link rel="stylesheet" href="../../css/style.css">
            </head>
            <?php include('../includes/headerNav.php')?>
            <body>
            <div class="container mt-5 mx-auto max-w-6xl">
                <h1 class="text-3xl font-bold mb-4"><?= $row['titre'] ?></h1>
                <p class="mb-2"><strong>Auteur :</strong> <?= $row['nom_auteur'] ?> | <strong>Date de création :</strong> <?= $row['date_creation'] ?></p>
                <p class="mb-8"><?= $row['contenu'] ?></p>
                <?php echo '<h2 class="text-2xl font-bold mb-4">Nombre de réponses : ' . $totalReponses . '</h2>';?>
                <div class="transition"></div>
                <?php
                // Récupération et affichage des réponses pour ce sujet
                echo '<div style="background-color: #0854C7;" class="p-4 rounded-md mb-4">';
                $sqlReponses = "SELECT reponses_forum.*, utilisateurs.userName AS nom_auteur_reponse, utilisateurs.photo_avatar
                                FROM reponses_forum
                                LEFT JOIN utilisateurs ON reponses_forum.id_utilisateur = utilisateurs.id
                                WHERE id_sujet = :id
                                ORDER BY reponses_forum.date_creation ASC
                                LIMIT :offset, :elementsParPage";
                $stmtReponses = $dbh->prepare($sqlReponses);
                $stmtReponses->bindParam(':id', $idSujet, PDO::PARAM_INT);
                $stmtReponses->bindParam(':offset', $offset, PDO::PARAM_INT);
                $stmtReponses->bindParam(':elementsParPage', $elementsParPage, PDO::PARAM_INT);
                $stmtReponses->execute();

                $sqlCount ="SELECT COUNT(*) AS total FROM reponses_forum WHERE id_sujet = :id";
                $stmtCount =$dbh->prepare($sqlCount);
                $stmtCount->bindParam(':id',$idSujet,PDO::PARAM_INT);
                $stmtCount->execute();
                $totalReponses =$stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

                if ($stmtReponses->rowCount() > 0) {
                    $reponses = $stmtReponses->fetchAll(PDO::FETCH_ASSOC);

                    foreach($reponses as $rowReponse) {
                        // Vérifier si l'utilisateur connecté est l'auteur de la réponse
                        $estAuteur = isset($_SESSION['nickName']) && $_SESSION['nickName'] === $rowReponse['nom_auteur_reponse'];
                        echo '<div class="response-item d-flex mb-4">';
                        echo '<div class="avatar-container mr-3">';
                        echo '<img src="' . $rowReponse['photo_avatar'] . '" alt="Avatar" style="width: 50px; height: 50px; border-radius: 50%;">';
                        echo '</div>';
                        echo '<div class="w-full"> ';
                        echo '<div class="d-flex">';

                        echo '<div class="auteur-info text-white">';
                        if ($rowReponse['id_sujet'] === $row['id']) {
                            echo $rowReponse['nom_auteur_reponse'] . ' - ' . $rowReponse['date_creation'] . ' - Auteur ';
                        } else {
                            echo $rowReponse['nom_auteur_reponse'] . ' - ' . $rowReponse['date_creation'];
                        }
                        echo '</div>';
                        echo '</div>';
                        //echo '<p class="text-white mb-2">' . $rowReponse['fonction_auteur'] . '</p>';
                        echo '<div class="bg-white shadow-md rounded-md p-4">';
                        echo '<p class="mb-2">' . $rowReponse['contenu'] . '</p>';
                        echo '<div class="flex justify-end">';

                        // Afficher le bouton de signalement si l'utilisateur n'est pas l'auteur de la réponse
                        if ($estAuteur || $_SESSION['id_role'] == 1) {
                            // Afficher les boutons de modification et de suppression si l'utilisateur est l'auteur de la réponse
                            echo '<div class="d-flex">';
                            echo '<a href="modifierReponse.php?id=' . $rowReponse['id_reponse'] . '&idSujet=' . $idSujet . '" class="btn btn-outline-info">Modifier</a>';
                            echo '<a href="supprimerReponse.php?id=' . $rowReponse['id_reponse'] . '&idSujet=' . $idSujet . '" class="btn btn-outline-danger">Supprimer</a>';
                            echo '</div>';

                        } else {
                            echo '<a href="#" class="inline-block btn btn-danger rounded-md btn-sm">Signaler</a>';
                        }
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                    }



                    // Affichage de la pagination
                    if ($totalPages > 1) {
                        echo '<nav aria-label="Pagination">';
                        echo '<ul class="pagination justify-content-center">';

                        // Bouton Précédent
                        echo '<li class="page-item '.($page <= 1 ? 'disabled' : '').'">';
                        echo '<a class="page-link" href="?id='.$idSujet.'&page='.($page - 1).'" tabindex="-1">Précédent</a>';
                        echo '</li>';

                        // Affichage des pages
                        $threshold = 5; // Seuil pour afficher les points de suspension
                        if ($totalPages <= $threshold) {
                            // Afficher toutes les pages si le nombre total est inférieur ou égal au seuil
                            for ($i = 1; $i <= $totalPages; $i++) {
                                echo '<li class="page-item '.($page == $i ? 'active' : '').'">';
                                echo '<a class="page-link" href="?id='.$idSujet.'&page='.$i.'">'.$i.'</a>';
                                echo '</li>';
                            }
                        } else {
                            // Afficher les pages avec les points de suspension
                            $start = max(1, $page - floor($threshold / 2));
                            $end = min($totalPages, $start + $threshold - 1);

                            if ($start > 1) {
                                echo '<li class="page-item">';
                                echo '<a class="page-link" href="?id='.$idSujet.'&page=1">1</a>';
                                echo '</li>';
                                if ($start > 2) {
                                    echo '<li class="page-item disabled">';
                                    echo '<span class="page-link">...</span>';
                                    echo '</li>';
                                }
                            }

                            for ($i = $start; $i <= $end; $i++) {
                                echo '<li class="page-item '.($page == $i ? 'active' : '').'">';
                                echo '<a class="page-link" href="?id='.$idSujet.'&page='.$i.'">'.$i.'</a>';
                                echo '</li>';
                            }

                            if ($end < $totalPages) {
                                if ($end < $totalPages - 1) {
                                    echo '<li class="page-item disabled">';
                                    echo '<span class="page-link">...</span>';
                                    echo '</li>';
                                }
                                echo '<li class="page-item">';
                                echo '<a class="page-link" href="?id='.$idSujet.'&page='.$totalPages.'">'.$totalPages.'</a>';
                                echo '</li>';
                            }
                        }

                        // Bouton Suivant
                        echo '<li class="page-item '.($page >= $totalPages ? 'disabled' : '').'">';
                        echo '<a class="page-link" href="?id='.$idSujet.'&page='.($page + 1).'">Suivant</a>';
                        echo '</li>';

                        echo '</ul>';
                        echo '</nav>';
                    }
                } else {
                    echo "<p class='text-white-500'>Aucune réponse trouvée pour ce sujet.</p>";
                }
                echo '</div>'; // Fermeture de la div de fond
                ?>
                <div class="transition"></div>
                <!-- Formulaire d'ajout de réponse -->
                <?php if (isset($_SESSION['nickName'])) : ?>
                    <div class="mt-5">
                        <h2 class="text-2xl font-bold mb-4">Ajouter une réponse</h2>
                        <form action="ajouterReponse.php" method="post">
                            <div class="form-group">
                                <label for="contenuReponse">Contenu de la réponse :</label>
                                <textarea class="form-control" id="contenuReponse" name="contenuReponse" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="id" value="<?= $idSujet ?>">
                            <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="container mt-5">
                        <div class="alert alert-warning" role="alert">
                            Connectez-vous pour pouvoir ajouter une réponse. <a href="../php/connexion.php" class="alert-link">Se connecter</a>.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php include('../includes/footer.php') ?>
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