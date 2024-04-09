<?php
/**
 * Script PHP pour afficher un sujet de forum avec ses réponses.
 *
 * Ce script récupère un sujet de forum spécifique et toutes ses réponses,
 * les affiche, et fournit un formulaire pour ajouter de nouvelles réponses.
 *
 * @package balance_ton_bully
 * @subpackage forum
 */

try {
    // Inclusion du fichier de connexion à la base de données
    include('../php/tools/functions.php'); // Connexion à la base de données
    $dbh = dbConnexion();
    session_start();

    // Afficher les informations de session
    // echo "<pre>";
    // var_dump($_SESSION);
    // echo "</pre>";

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
                WHERE  sujets_forum.id = :id";
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
                <title>Sujet du forum</title>
                <?php include('../includes/headLink.php') ?>
            </head>
            <body>
            <?php include('../includes/headerNav.php')?>
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
                echo $rowReponse['nom_auteur_reponse'] . ' - ' . $rowReponse['date_creation'] . ' - Auteur '; // -------------------------------------- à vérifier car cela apparait sur toutes les réponses
            } else {
                echo $rowReponse['nom_auteur_reponse'] . ' - ' . $rowReponse['date_creation'];
            }
            echo '</div>';
            echo '</div>';
            // echo '<p class="text-white mb-2">' . $rowReponse['fonction_auteur'] . '</p>';
            echo '<div class="bg-white shadow-md rounded-md p-4">';
            echo '<p class="mb-2">' . $rowReponse['contenu'] . '</p>';
            echo '<div class="flex justify-end">';

            // Afficher le bouton de signalement si l'utilisateur n'est pas l'auteur de la réponse

            if (!$estAuteur) {
                echo '<button onclick="signalerReponse(' . $rowReponse['id_reponse'] . ')" class="btn btn-danger btn-sm">Signaler</button>';
            } else {

                // Afficher les boutons de modification et de suppression si l'utilisateur est l'auteur de la réponse
                echo '<div class="d-flex">';
                echo '<a href="modifierReponse.php?id=' . $rowReponse['id_reponse'] . '&idSujet=' . $idSujet . '" class="btn btn-outline-info">Modifier</a>';
                echo '<a href="../php/supprimerReponse.php?id=' . $rowReponse['id_reponse'] . '&idSujet=' . $idSujet . '" class="btn btn-outline-danger">Supprimer</a>';
                echo '</div>';



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
                echo '</div>'; // Fermeture de la div de fond
                ?>
                <div class="transition"></div>
                <!-- Formulaire d'ajout de réponse -->
                <?php if (isset($_SESSION['nickName'])) : ?>
                    <div class="mt-5">
                        <h2 class="text-2xl font-bold mb-4">Ajouter une réponse</h2>
                        <form action="../php/ajouterReponse.php" method="post">
                            <div class="form-group">
                                <label for="contenuReponse">Contenu de la réponse :</label>
                                <textarea class="form-control" id="contenuReponse" name="contenuReponse" rows="4" required></textarea>
                            </div>
                            <input type="hidden" name="idSujet" value="<?= $idSujet ?>">
                            <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
                        </form>
                    </div>
                <?php else : ?>
                    <div class="container mt-5">
                        <div class="alert alert-warning" role="alert">
                            Connectez-vous pour pouvoir ajouter une réponse. <a href="connexion.php" class="alert-link">Se connecter</a>.
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            <?php include('../includes/footer.php') ?>
            <?php include('../includes/scriptLink.php') ?>
            <script>
                /**
                 * Fonction JavaScript pour signaler une réponse sur un forum.
                 *
                 * Cette fonction envoie une requête AJAX pour signaler une réponse spécifique.
                 * Elle demande une confirmation à l'utilisateur avant de procéder.
                 *
                 * @param {number} idReponse - Identifiant de la réponse à signaler.
                 */
                function signalerReponse(idReponse) {
                    // Confirmation avant le signalement
                    if (confirm('Voulez-vous vraiment signaler cette réponse ?')) {
                        // Requête AJAX pour le signalement
                        fetch('../pages/signalerReponse.php', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: 'idReponse=' + idReponse
                        })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('La réponse a été signalée avec succès.');
                                } else {
                                    alert('Erreur lors du signalement.');
                                }
                            })
                            .catch(error => console.error('Erreur:', error));
                    }
                }

            </script>
            </body>
            </html>
            <?php
        } else {
            echo "<p class='text-gray-500'>Aucun sujet trouvé avec cet identifiant.</p>";
        }
    } else {
        echo "<p class='text-gray-500'>Aucune réponse trouvée pour ce sujet.</p>";
    }
    echo '</div>'; // Fermeture de la div de fond
    ?>
    <div class="transition"></div>
    <!-- Formulaire d'ajout de réponse -->
    <?php if (isset($_SESSION['nickName'])) : ?>
        <div class="mt-5">
            <h2 class="text-2xl font-bold mb-4">Ajouter une réponse</h2>
            <form action="../php/ajouterReponse.php" method="post">
                <div class="form-group">
                    <label for="contenuReponse">Contenu de la réponse :</label>
                    <textarea class="form-control" id="contenuReponse" name="contenuReponse" rows="4" required></textarea>
                </div>
                <input type="hidden" name="idSujet" value="<?= $idSujet ?>">
                <button type="submit" class="btn btn-primary mt-3">Envoyer</button>
            </form>
        </div>
    <?php else : ?>
        <div class="container mt-5">
            <div class="alert alert-warning" role="alert">
                Connectez-vous pour pouvoir ajouter une réponse. <a href="connexion.php" class="alert-link">Se connecter</a>.
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
<script>
/**
* Fonction JavaScript pour signaler une réponse sur un forum.
*
* Cette fonction envoie une requête AJAX pour signaler une réponse spécifique.
* Elle demande une confirmation à l'utilisateur avant de procéder.
*
* @param {number} idReponse - Identifiant de la réponse à signaler.
*/
function signalerReponse(idReponse) {
// Confirmation avant le signalement
if (confirm('Voulez-vous vraiment signaler cette réponse ?')) {
// Requête AJAX pour le signalement
fetch('../pages/signalerReponse.php', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/x-www-form-urlencoded',
    },
    body: 'idReponse=' + idReponse
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        alert('La réponse a été signalée avec succès.');
    } else {
        alert('Erreur lors du signalement.');
    }
})
.catch(error => console.error('Erreur:', error));
}
}

</script>