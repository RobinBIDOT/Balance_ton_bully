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
 * Obtient le nombre total d'actualités dans la base de données, éventuellement filtré par un mot-clé.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param string $motCle Mot-clé pour filtrer les actualités.
 * @return int Nombre total d'actualités correspondant au mot-clé.
 */
function getTotalActualites($dbh, $motCle = '') {
    if ($motCle) {
        $sql = "SELECT COUNT(*) AS total FROM actualites WHERE titre LIKE :motCle OR contenu LIKE :motCle";
        $stmt = $dbh->prepare($sql);
        $motCleParam = '%' . $motCle . '%';
        $stmt->bindParam(':motCle', $motCleParam, PDO::PARAM_STR);
    } else {
        $sql = "SELECT COUNT(*) AS total FROM actualites";
        $stmt = $dbh->prepare($sql);
    }
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row['total'];
}

// Récupération du mot-clé de recherche s'il est défini
$motCle = isset($_GET['recherche']) ? $_GET['recherche'] : '';

// Obtenir le nombre total d'actualités en tenant compte de la recherche
$totalActualites = getTotalActualites($dbh, $motCle);

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

/**
 * Recherche des actualités en fonction d'un mot-clé et retourne les résultats paginés.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param string $motCle Mot-clé pour la recherche.
 * @param int $offset Décalage initial pour la pagination.
 * @param int $elementsParPage Nombre d'éléments par page.
 * @return array Résultats de la recherche.
 */
function rechercherActualites($dbh, $motCle, $offset, $elementsParPage) {
    if ($motCle) {
        // Recherche avec mot-clé
        $sql = "SELECT * FROM actualites WHERE titre LIKE :motCle OR contenu LIKE :motCle ORDER BY date_publication DESC LIMIT :offset, :elementsParPage";
        $stmt = $dbh->prepare($sql);
        $motCleParam = '%' . $motCle . '%';
        $stmt->bindParam(':motCle', $motCleParam, PDO::PARAM_STR);
    } else {
        // Pas de mot-clé, sélection de toutes les actualités
        $sql = "SELECT * FROM actualites ORDER BY date_publication DESC LIMIT :offset, :elementsParPage";
        $stmt = $dbh->prepare($sql);
    }

    // Liaison des paramètres de pagination et exécution de la requête
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':elementsParPage', $elementsParPage, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Appel de la fonction pour obtenir les actualités
$actualities = rechercherActualites($dbh, $motCle, $offset, $elementsParPage);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-compatible" content="ie=edge">
    <title>Balance Ton Bully - Actualités</title>
    <?php include('../includes/headLink.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.min.css" rel="stylesheet">
</head>
<body>
<?php include('../includes/headerNav.php')?>
<div class="container mt-4">
    <h2 class="mb-3 text-center">Toute l'actualité</h2>
    <!-- Formulaire de recherche -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="form-inline">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Rechercher..." name="recherche" aria-label="Rechercher">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="submit" id="button-addon2"><i class="fa-solid fa-magnifying-glass"></i></button>
                <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-outline-danger"><i class="fa-solid fa-magnifying-glass-arrow-right"></i></a>
            </div>
        </div>
    </form>
    <?php foreach ($actualities as $key => $actu):
        // Création d'un objet DateTime à partir de la date de publication
        $datePublication = new DateTime($actu['date_publication']);
        // Formatage de la date au format JJ/MM/AAAA
        $formattedDate = $datePublication->format('d/m/Y');
        ?>
        <div class="row no-gutters border rounded overflow-hidden mb-4
         <?php echo $key % 2 == 0 ? '' : 'flex-md-row-reverse'; ?>"
             data-aos="<?php echo $key % 2 == 0 ? 'fade-right' : 'fade-left'; ?>"
             data-aos-delay="<?php echo $key * 100; ?>"
             style="background-color: #0854C7;">
            <div class="col-12 col-md-auto text-center">
                <img src="<?php echo htmlspecialchars($actu['photo']); ?>"
                     style="height: 250px;"
                     alt="photo actu"
                     class="my-3">
            </div>
            <div class="col p-4">
                <div class="bg-white p-3 rounded">
                    <h5 class="card-title mb-3 text-justify"><?php echo htmlspecialchars($actu['titre']); ?></h5>
                    <p class="card-text text-justify"><?php echo htmlspecialchars($actu['contenu']); ?></p>
                    <p class="card-text"><small class="text-muted"><?php echo $formattedDate; ?></small></p>
                    <div class="d-flex justify-content-end">
                        <a href="<?php echo htmlspecialchars($actu['lien_article']); ?>"
                           target="_blank"
                           class="btn btn-link">...Voir l'article</a>
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
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
<script>
    AOS.init();
</script>
</body>
</html>