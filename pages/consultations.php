<?php
// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Récupérer les données des professionnels de la santé avec leurs expertises depuis la base de données
$sql = "SELECT ps.id AS professionnel_id,
           ps.nom,
           ps.prenom,
           ps.profession,
           ps.adresse,
           ps.ville,
           ps.code_postal,
           ps.presentation,
           ps.photo,
           GROUP_CONCAT(DISTINCT e.nom) AS expertises
    FROM professionnels_sante ps
    LEFT JOIN professionnel_expertise pe ON ps.id = pe.professionnel_id
    LEFT JOIN expertise e ON pe.expertise_id = e.id
    GROUP BY ps.id;";
$stmt = $dbh->query($sql);
$professionnels = array();

// Récupérer les données sous forme de tableau associatif
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $id = $row['professionnel_id'];
    if (!isset($professionnels[$id])) {
        $professionnels[$id] = array(
            'id' => $id,
            'prenom' => $row['prenom'],
            'nom' => $row['nom'],
            'profession' => $row['profession'],
            'adresse' => $row['adresse'],
            'ville' => $row['ville'],
            'code_postal' => $row['code_postal'],
            'photo' => $row['photo'],
            'presentation' => $row['presentation'],
            'expertises' => array()
        );
    }
    // Ajouter chaque expertise au tableau d'expertises du professionnel
    if (!empty($row['expertises'])) {
        $expertises = explode(',', $row['expertises']);
//        var_dump($expertises);
        foreach ($expertises as $expertise) {
            $professionnels[$id]['expertises'][] = $expertise;
//            var_dump($expertise);
        }
    }else {
        error_log("Expertises are empty for professional ID: $id"); // Log si les expertises sont vides
    }
}

// Définition des paramètres de pagination
$elementsParPage = 5; // Nombre d'éléments par page
$page = isset($_GET['page']) ? intval($_GET['page']) : 1; // Page actuelle, par défaut page 1
$offset = ($page - 1) * $elementsParPage; // Calcul du décalage

// Comptage du nombre total de professionnels de la santé
$sqlCount = "SELECT COUNT(DISTINCT ps.id) AS total FROM professionnels_sante ps LEFT JOIN professionnel_expertise pe ON ps.id = pe.professionnel_id LEFT JOIN expertise e ON pe.expertise_id = e.id WHERE 1=1";
$nomRecherche = isset($_GET['nom']) ? $_GET['nom'] : null;
$villeRecherche = isset($_GET['ville']) ? $_GET['ville'] : null;
if ($nomRecherche) {
    $sqlCount .= " AND ps.nom LIKE :nom";
}
if ($villeRecherche) {
    $sqlCount .= " AND ps.ville LIKE :ville";
}
$stmtCount = $dbh->prepare($sqlCount);
if ($nomRecherche) {
    $nomParam = '%' . $nomRecherche . '%';
    $stmtCount->bindParam(':nom', $nomParam, PDO::PARAM_STR);
}
if ($villeRecherche) {
    $villeParam = '%' . $villeRecherche . '%';
    $stmtCount->bindParam(':ville', $villeParam, PDO::PARAM_STR);
}
$stmtCount->execute();
$total = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

// Calcul du nombre total de pages
$totalPages = ceil($total / $elementsParPage);

// Fonction pour obtenir les professionnels de santé pour une page donnée avec possibilité de recherche et tri
function getProfessionnels($dbh, $offset, $elementsParPage, $nom = null, $ville = null) {
    $sql = "SELECT ps.*, GROUP_CONCAT(e.nom) AS expertises FROM professionnels_sante ps LEFT JOIN professionnel_expertise pe ON ps.id = pe.professionnel_id LEFT JOIN expertise e ON pe.expertise_id = e.id WHERE 1=1";
    if ($nom) {
        $sql .= " AND ps.nom LIKE :nom";
    }
    if ($ville) {
        $sql .= " AND ps.ville LIKE :ville";
    }
    $sql .= " GROUP BY ps.id ORDER BY ps.nom ASC, ps.ville ASC LIMIT :offset, :elementsParPage";

    $stmt = $dbh->prepare($sql);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':elementsParPage', $elementsParPage, PDO::PARAM_INT);
    if ($nom) {
        $nomParam = '%' . $nom . '%';
        $stmt->bindParam(':nom', $nomParam, PDO::PARAM_STR);
    }
    if ($ville) {
        $villeParam = '%' . $ville . '%';
        $stmt->bindParam(':ville', $villeParam, PDO::PARAM_STR);
    }
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Obtention des professionnels de santé pour la page actuelle avec recherche
$professionnels = getProfessionnels($dbh, $offset, $elementsParPage, $nomRecherche, $villeRecherche);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consultations</title>
    <?php include('../includes/headLink.php') ?>
    <script src="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/aos@2.3.4/dist/aos.css" rel="stylesheet">
    <link rel="stylesheet" href="../css/styleConsultations.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container">
    <div class="jumbotron mt-3 blue">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h1 class="display-4 text-center">Planifiez votre prochaine consultation en découvrant des professionnels de la santé adaptés à vos besoins</h1>
                <hr class="my-4">
                <!-- Formulaire de recherche -->
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get" class="form-inline mb-3">
                    <div class="input-group mr-2">
                        <input type="text" class="form-control" placeholder="Nom du médecin" name="nom" aria-label="Nom du médecin">
                    </div>
                    <div class="input-group mr-2">
                        <input type="text" class="form-control" placeholder="Ville" name="ville" aria-label="Ville">
                    </div>
                    <div class="input-group">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                        <a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="btn btn-outline-danger"><i class="fa-solid fa-magnifying-glass-arrow-right"></i></a>
                    </div>
                </form>
            </div>
            <div class="col-md-4 text-center">
                <img src="../assets/prosante.png" alt="Image ProSante" style="max-width: 100%; height: auto;">
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row">
        <?php foreach ($professionnels as $key => $professionnel): ?>
            <div class="jumbotron mt-2 mb-2 blue <?php echo $key % 2 == 0 ? '' : 'flex-md-row-reverse'; ?>"
                 data-aos="<?php echo $key % 2 == 0 ? 'fade-right' : 'fade-left'; ?>"
                 data-aos-delay="<?php echo $key * 100; ?>">
                <div class="row no-gutters align-items-center">
                    <div class="col-md-4 mt-1 mb-1">
                        <div class="ml-3">
                            <img src="<?php echo $professionnel['photo']; ?>" class="card-img-top img-thumbnail img-fluid mb-1" alt="Photo de <?php echo $professionnel['prenom'] . ' ' . $professionnel['nom']; ?>" style="max-width: 100px;">
                            <h5 class="card-title"><?php echo $professionnel['prenom'] . ' ' . $professionnel['nom']; ?></h5>
                            <p class="card-text mb-1"><?php echo $professionnel['profession']; ?></p>
                            <p class="card-text mb-1"><?php echo $professionnel['adresse']; ?></p>
                            <p class="card-text mb-1"><?php echo $professionnel['ville']; ?></p>
                            <p class="card-text mb-1"><?php echo $professionnel['code_postal']; ?></p>
                            <a href="../pages/rdv.php?professionnel_id=<?php echo $professionnel['id']; ?>" class="btn btn-primary btn-sm mb-1">Prendre rendez-vous</a>
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="card-footer blue">
                            <div class="col">
                                <div class="col mb-2">
                                    <h6 class="col mb-2">Expertises :</h6>
                                    <ul class="list-inline" style="margin-bottom: 0;">
                                        <?php
                                        if(isset($professionnel['expertises'])) {
                                            // Si $professionnel['expertises'] est une chaîne, convertissons-la en un tableau
                                            $expertises = is_array($professionnel['expertises']) ? $professionnel['expertises'] : explode(',', $professionnel['expertises']);
                                            foreach ($expertises as $expertise) {
                                                echo '<li class="list-inline-item expertise-item">';
                                                echo htmlspecialchars($expertise);
                                                echo '</li>';
                                            }
                                        }
                                        ?>
                                    </ul>
                                </div>
                                <div class="col mt-3">
                                    <h6 class="col mb-2">Présentation :</h6>
                                    <p><?php echo $professionnel['presentation']; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<!-- Pagination -->
<nav aria-label="Pagination">
    <ul class="pagination justify-content-center">
        <?php if (isset($totalPages) && is_numeric($totalPages) && $totalPages > 1): ?>
            <!-- Bouton Précédent -->
            <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo ($page - 1); ?>" tabindex="-1">Précédent</a>
            </li>

            <?php
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
            ?>

            <!-- Bouton Suivant -->
            <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                <a class="page-link" href="?page=<?php echo ($page + 1); ?>">Suivant</a>
            </li>
        <?php endif; ?>
    </ul>
</nav>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    AOS.init();
</script>
</body>
</html>