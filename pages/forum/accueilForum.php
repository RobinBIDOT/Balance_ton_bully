<?php
try {
    // Inclusion du fichier de connexion à la base de données
    include('../../php/tools/functions.php');
    $dbh = dbConnexion();

    // Pagination
    $limit = 10; // Nombre de sujets par page
    $page = isset($_GET['page']) ? $_GET['page'] : 1; // Page actuelle

    // Comptage total des sujets
    $countSql = "SELECT COUNT(*) AS total FROM sujets_forum";
    $stmt = $dbh->query($countSql);
    $totalRows = $stmt->fetch(PDO::FETCH_ASSOC)['total'];

    // Calcul du nombre total de pages
    $totalPages = ceil($totalRows / $limit);

    // Offset
    $offset = ($page - 1) * $limit;

    // Récupération des sujets du forum pour la page actuelle
    $sql = "SELECT sujets_forum.*, COUNT(reponses_forum.id_reponse) AS nombre_reponses, utilisateurs.pseudo, utilisateurs.photo_avatar
            FROM sujets_forum
            LEFT JOIN reponses_forum ON sujets_forum.id_sujet = reponses_forum.id_sujet
            LEFT JOIN utilisateurs ON sujets_forum.id_utilisateur = utilisateurs.id_utilisateur";

    // Construction de la clause WHERE pour la recherche
    $searchTitle = isset($_GET['searchTitle']) ? trim($_GET['searchTitle']) : '';
    $searchUser = isset($_GET['searchUser']) ? trim($_GET['searchUser']) : '';
    if (!empty($searchTitle) || !empty($searchUser)) {
        $sql .= " WHERE ";
        $conditions = [];
        if (!empty($searchTitle)) {
            $conditions[] = "titre LIKE '%$searchTitle%'";
        }
        if (!empty($searchUser)) {
            $conditions[] = "utilisateurs.pseudo LIKE '%$searchUser%'";
        }
        $sql .= implode(" AND ", $conditions);
    }

    $sql .= " GROUP BY sujets_forum.id_sujet
              ORDER BY sujets_forum.date_creation DESC
              LIMIT $limit OFFSET $offset";

    $result = $dbh->query($sql);
} catch (PDOException $e) {
    // Gestion des erreurs PDO
    echo "Erreur PDO : " . $e->getMessage();
    exit(); // Arrêt du script en cas d'erreur
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil du forum</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../../css/style.css">
    <style>
        .jumbotron-primary {
            background-color: #8BBFFE;
            color: white;
        }

        .container-primary {
            background-color: #0854C7;
            color: white;
        }

        .container-secondary {
            background-color: #0854C7;
        }

        .avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            margin-right: 10px;
        }

        .sujet {
            margin-bottom: 20px;
        }

        .nombre-reponses {
            margin-left: auto;
        }

        .sujets-container {
            background-color: #0854C7;
            padding: 20px;
            border-radius: 10px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <div class="jumbotron jumbotron-primary">
        <h1 class="display-4">Bienvenue sur le forum de Balance ton bully</h1>
        <p class="lead">Voici un espace d'échange pour discuter des différents sujets concernant le phénomène de harcèlement et de cyber-harcèlement. Respectez les règles du forum et contribuez à créer un environnement sûr et bienveillant pour tous.</p>
        <hr class="my-4">
        <h3>Règles du forum :</h3>
        <p>1. Respectez les autres membres du forum.</p>
        <p>2. Évitez les propos offensants, agressifs ou discriminatoires.</p>
        <p>3. N'abusez pas des majuscules ni des caractères spéciaux.</p>
        <p>4. Signalez tout contenu inapproprié ou non conforme aux règles.</p>
    </div>
    <br>
    <!-- Formulaire de recherche -->
    <form method="GET" action="">
        <div class="mb-3">
            <label for="searchTitle" class="form-label">Rechercher par titre :</label>
            <input type="text" class="form-control" id="searchTitle" name="searchTitle" placeholder="Entrez un titre">
        </div>
        <div class="mb-3">
            <label for="searchUser" class="form-label">Rechercher par utilisateur :</label>
            <input type="text" class="form-control" id="searchUser" name="searchUser" placeholder="Entrez un nom d'utilisateur">
        </div>
        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Rechercher</button>
            <a href="accueilForum.php" class="btn btn-secondary">Réinitialiser</a>
        </div>
    </form>

    <br>
    <div class="transition"></div>
    <div class="sujets-container">
        <div class="list-group">
            <?php if ($result->rowCount() > 0) : ?>
                <?php while($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a href="sujet.php?id=<?php echo $row['id_sujet']; ?>" class="list-group-item sujet">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $row['photo_avatar']; ?>" alt="Avatar" class="avatar mr-3">
                            <div>
                                <h5 class="mb-1"><?php echo $row['titre']; ?></h5>
                                <small><?php echo $row['pseudo']; ?> - <?php echo $row['date_creation']; ?> </small>
                            </div>
                            <div class="nombre-reponses">
                                <span class="badge bg-primary"><?php echo $row['nombre_reponses']; ?> Réponses</span>
                            </div>
                        </div>
                    </a>
                <?php endwhile; ?>
            <?php else : ?>
                <p>Aucun sujet trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <?php if ($totalPages > 1) : ?>
        <!-- Affichage de la pagination -->
        <nav aria-label="Pagination" class="mt-4">
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo $page <= 1 ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>" tabindex="-1">Précédent</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                    <?php
                    // Afficher uniquement les pages proches de la page actuelle plus quelques pages autour
                    $pageDelta = 2;
                    if ($i >= $page - $pageDelta && $i <= $page + $pageDelta) :
                        ?>
                        <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php elseif ($i == 1 || $i == $totalPages) : ?>
                        <!-- Afficher toujours la première et la dernière page -->
                        <li class="page-item">
                            <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                        </li>
                    <?php elseif ($i == $page - $pageDelta - 1 || $i == $page + $pageDelta + 1) : ?>
                        <!-- Afficher des points de suspension pour les pages éloignées -->
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    <?php endif; ?>
                <?php endfor; ?>
                <li class="page-item <?php echo $page >= $totalPages ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Suivant</a>
                </li>
            </ul>
        </nav>
    <?php endif; ?>
    <br>
    <div class="transition"></div>
</div>

<div class="container mt-5">
    <!-- Formulaire de création de sujet -->
    <div class="card">
        <div class="card-header">
            Créer un nouveau sujet
        </div>
        <div class="card-body">
            <form method="post" action="ajouterSujet.php">
                <div class="form-group">
                    <label for="titre">Titre du sujet</label>
                    <input type="text" class="form-control" id="titre" name="titre" required>
                </div>
                <div class="form-group">
                    <label for="contenu">Contenu du sujet</label>
                    <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Créer</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>
