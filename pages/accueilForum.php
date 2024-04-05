<?php

/**
 * Compte le nombre total de sujets dans la base de données.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @return int Nombre total de sujets.
 */
function compterSujets($dbh) {
    $countSql = "SELECT COUNT(*) AS total FROM sujets_forum";
    $stmt = $dbh->query($countSql);
    return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
}

/**
 * Récupère les sujets du forum pour la page donnée.
 *
 * @param PDO $dbh Connexion à la base de données.
 * @param int $limit Nombre de sujets par page.
 * @param int $offset Décalage pour la pagination.
 * @param string $searchTitle Titre à rechercher.
 * @param string $searchUser Utilisateur à rechercher.
 * @return PDOStatement Résultat de la requête.
 */
function recupererSujets($dbh, $limit, $offset, $searchTitle, $searchUser) {
    $sql = "SELECT sujets_forum.*, COUNT(reponses_forum.id_reponse) AS nombre_reponses, utilisateurs.userName, utilisateurs.photo_avatar
            FROM sujets_forum
            LEFT JOIN reponses_forum ON sujets_forum.id = reponses_forum.id_sujet
            LEFT JOIN utilisateurs ON sujets_forum.id_utilisateur = utilisateurs.id";

    if (!empty($searchTitle) || !empty($searchUser)) {
        $sql .= " WHERE ";
        $conditions = [];
        if (!empty($searchTitle)) {
            $conditions[] = "titre LIKE :searchTitle";
        }
        if (!empty($searchUser)) {
            $conditions[] = "utilisateurs.userName LIKE :searchUser";
        }
        $sql .= implode(" AND ", $conditions);
    }

    $sql .= " GROUP BY sujets_forum.id
              ORDER BY sujets_forum.date_creation DESC
              LIMIT :limit OFFSET :offset";

    $stmt = $dbh->prepare($sql);
    if (!empty($searchTitle)) {
        $stmt->bindValue(':searchTitle', "%$searchTitle%", PDO::PARAM_STR);
    }
    if (!empty($searchUser)) {
        $stmt->bindValue(':searchUser', "%$searchUser%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt;
}

try {
    include('../php/tools/functions.php');
    $dbh = dbConnexion();
    session_start();

    $limit = 10; // Nombre de sujets par page
    $page = isset($_GET['page']) ? $_GET['page'] : 1;
    $offset = ($page - 1) * $limit;

    // Utilisation de la fonction compterSujets pour obtenir le nombre total de sujets
    $totalRows = compterSujets($dbh);
    $totalPages = ceil($totalRows / $limit);

    $searchTitle = isset($_GET['searchTitle']) ? trim($_GET['searchTitle']) : '';
    $searchUser = isset($_GET['searchUser']) ? trim($_GET['searchUser']) : '';

    // Utilisation de la fonction recupererSujets pour obtenir les sujets du forum
    $result = recupererSujets($dbh, $limit, $offset, $searchTitle, $searchUser);

} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil du forum</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleForum.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mt-5">
    <div class="jumbotron jumbotron-fluid jumbotron-primary text-white rounded-3 p-5 shadow">
        <h1 class="display-4">Bienvenue sur le forum de Balance ton bully</h1>
        <p class="lead">Voici un espace d'échange pour discuter des différents sujets concernant le phénomène de harcèlement et de cyber-harcèlement. Respectez les règles du forum et contribuez à créer un environnement sûr et bienveillant pour tous.</p>
        <hr class="my-4 bg-light">
        <h3>Règles du forum :</h3>
        <p>1. Respectez les autres membres du forum.</p>
        <p>2. Évitez les propos offensants, agressifs ou discriminatoires.</p>
        <p>3. N'abusez pas des majuscules ni des caractères spéciaux.</p>
        <p>4. Signalez tout contenu inapproprié ou non conforme aux règles.</p>
    </div>
    <br>
    <!-- Formulaire de recherche -->
    <form method="GET">
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
                <!-- Boucle pour afficher les sujets -->
                <?php while($row = $result->fetch(PDO::FETCH_ASSOC)) : ?>
                    <a href="sujet.php?id=<?php echo $row['id']; ?>" class="list-group-item sujet">
                        <div class="d-flex align-items-center">
                            <img src="<?php echo $row['photo_avatar']; ?>" alt="Avatar" class="avatar mr-3">
                            <div>
                                <h5 class="mb-1"><?php echo $row['titre']; ?></h5>
                                <small><?php echo $row['userName']; ?> - <?php echo $row['date_creation']; ?> </small>
                            </div>
                            <div class="nombre-reponses">
                                <span class="text-primary" style="cursor: pointer;" >
                                    <?php if ($row['nombre_reponses'] > 1){
                                        echo $row['nombre_reponses'] ?> Réponses</span> <?php
                                    } else {
                                       echo $row['nombre_reponses']; ?> Réponse</span> <?php
                                    }?>
                            </div>
                            <!-- Bouton Supprimer -->
                            <?php if(isset($_SESSION['nickName']) && ($_SESSION['nickName'] === $row['userName'] || $_SESSION['id_role'] == 1)) : ?>
                                <form action="../php/supprimerSujet.php" method="post">
                                    <input type="hidden" name="id_sujet" id="id_sujet" value="<?php echo $row['id']; ?>">
                                    <input type="submit" id="delContent" class="btn btn-danger btn-sm ms-2" value="Supprimer">
                                </form>
                            <?php endif; ?>
                        </div>
                    </a>
                <?php endwhile; ?>

            <?php else : ?>
                <p>Aucun sujet trouvé.</p>
            <?php endif; ?>
        </div>
    </div>
    <br>
    <?php
    if (empty($_GET['searchTitle']) && empty($_GET['searchUser']) && ($totalPages > 1) ): ?>
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
<?php
// Vérifier si l'utilisateur est connecté pour afficher le formulaire de création de sujet
if(isset($_SESSION['nickName'])) :
    ?>
    <div class="container mt-5">
        <!-- Formulaire de création de sujet -->
        <div class="card">
            <div class="card-header">
                Créer un nouveau sujet
            </div>
            <div class="card-body">
                <form method="post" action="../php/ajouterSujet.php">
                    <div class="form-group">
                        <label for="titre">Titre du sujet</label>
                        <input type="text" class="form-control" id="titre" name="titre" required>
                    </div>
                    <div class="form-group">
                        <label for="contenu">Contenu du sujet</label>
                        <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary mt-2">Créer</button>
                </form>
            </div>
        </div>
    </div>
<?php else : ?>
    <div class="container mt-5">
        <div class="alert alert-warning" role="alert">
            Connectez-vous pour pouvoir ajouter une réponse. <a href="connexion.php" class="alert-link">Se connecter</a>.
        </div>
    </div>
<?php endif; ?>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>