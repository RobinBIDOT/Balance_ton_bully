<?php
/**
 * Ce script affiche une liste d'actualités avec pagination.
 * Il récupère les actualités depuis une base de données et les affiche par pages.
 */

// Inclusion du fichier de connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Définition des paramètres de pagination
$elementsParPage = 5;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $elementsParPage;

/**
 * Obtient le nombre total d'actualités dans la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return int Nombre total d'actualités.
 */
function getTotalActualites($dbh) {
    $stmt = $dbh->prepare("SELECT COUNT(*) AS total FROM actualites");
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

// Obtenir le nombre total d'actualités
$totalActualites = getTotalActualites($dbh);

// Calculer le nombre total de pages
$totalPages = ceil($totalActualites / $elementsParPage);

/**
 * Récupère les actualités pour une page donnée.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $offset Décalage initial pour la requête SQL.
 * @param int $elementsParPage Nombre d'éléments par page.
 * @return array Liste des actualités pour la page actuelle.
 */
function getActualites($dbh, $offset, $elementsParPage) {
    $stmt = $dbh->prepare("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT :offset, :elementsParPage");
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':elementsParPage', $elementsParPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

$actualities = getActualites($dbh, $offset, $elementsParPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-compatible" content="ie-edge">
    <title>Balance Ton Bully - Actualités</title>
    <link href="../css/style.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php include('../includes/headerNav.php')?>
<div class="container mt-4">
    <h2 class="mb-3 text-center">Toute l'actualité</h2>
    <?php foreach ($actualities as $key => $actu): ?>
        <div class="row no-gutters border rounded overflow-hidden mb-4 <?php echo $key % 2 == 0 ? '' : 'flex-md-row-reverse'; ?>" style="background-color: #0854C7;">
            <div class="col-12 col-md-auto text-center">
                <img src="<?php echo htmlspecialchars($actu['photo']); ?>" style="height: 250px;" alt="photo actu" class="my-3">
            </div>
            <div class="col p-4">
                <div class="bg-white p-3 rounded">
                    <h5 class="card-title"><?php echo htmlspecialchars($actu['titre']); ?></h5>
                    <p class="card-text text-start"><?php echo htmlspecialchars($actu['contenu']); ?></p>
                    <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($actu['date_publication']); ?></small></p>
                    <div class="d-flex justify-content-end">
                        <a href="<?php echo htmlspecialchars($actu['lien_article']); ?>" target="_blank" class="btn btn-link">...Voir l'article</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    <?php
    // Affichage de la pagination
    if ($totalPages > 1) {
        echo '<nav aria-label="Pagination">';
        echo '<ul class="pagination justify-content-center">';

        // Bouton Précédent
        echo '<li class="page-item '.($page <= 1 ? 'disabled' : '').'">';
        echo '<a class="page-link" href="?page='.($page - 1).'" tabindex="-1">Précédent</a>';
        echo '</li>';

        // Affichage des pages
        $threshold = 5; // Seuil pour afficher les points de suspension
        if ($totalPages <= $threshold) {
            // Afficher toutes les pages si le nombre total est inférieur ou égal au seuil
            for ($i = 1; $i <= $totalPages; $i++) {
                echo '<li class="page-item '.($page == $i ? 'active' : '').'">';
                echo '<a class="page-link" href="?page='.$i.'">'.$i.'</a>';
                echo '</li>';
            }
        } else {
            // Afficher les pages avec les points de suspension
            $start = max(1, $page - floor($threshold / 2));
            $end = min($totalPages, $start + $threshold - 1);

            if ($start > 1) {
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?page=1">1</a>';
                echo '</li>';
                if ($start > 2) {
                    echo '<li class="page-item disabled">';
                    echo '<span class="page-link">...</span>';
                    echo '</li>';
                }
            }

            for ($i = $start; $i <= $end; $i++) {
                echo '<li class="page-item '.($page == $i ? 'active' : '').'">';
                echo '<a class="page-link" href="?page='.$i.'">'.$i.'</a>';
                echo '</li>';
            }

            if ($end < $totalPages) {
                if ($end < $totalPages - 1) {
                    echo '<li class="page-item disabled">';
                    echo '<span class="page-link">...</span>';
                    echo '</li>';
                }
                echo '<li class="page-item">';
                echo '<a class="page-link" href="?page='.$totalPages.'">'.$totalPages.'</a>';
                echo '</li>';
            }
        }

        // Bouton Suivant
        echo '<li class="page-item '.($page >= $totalPages ? 'disabled' : '').'">';
        echo '<a class="page-link" href="?page='.($page + 1).'">Suivant</a>';
        echo '</li>';

        echo '</ul>';
        echo '</nav>';
    }
    ?>
</div>
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script> -->
<?php include('../includes/footer.php') ?>
</body>
</html>