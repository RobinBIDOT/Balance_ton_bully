<?php
try {
    // Inclusion du fichier de connexion à la base de données
    global $dbh;
    include('../../php/connect.php');

    // Récupération des sujets du forum
    $sql = "SELECT sujets_forum.*, COUNT(reponses_forum.id_reponse) AS nombre_reponses, utilisateurs.nom_utilisateur
            FROM sujets_forum
            LEFT JOIN reponses_forum ON sujets_forum.id_sujet = reponses_forum.id_sujet
            LEFT JOIN utilisateurs ON sujets_forum.id_utilisateur = utilisateurs.id_utilisateur
            GROUP BY sujets_forum.id_sujet
            ORDER BY sujets_forum.date_creation DESC";
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
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

    <form class="form-inline mb-4">
        <input class="form-control mr-sm-2" type="search" placeholder="Rechercher un sujet" aria-label="Search">
        <button class="btn btn-outline-primary my-2 my-sm-0" type="submit">Rechercher</button>
    </form>

    <h1>Accueil du forum</h1>
    <div class="container-primary">
        <div class="list-group">
            <?php
            // Affichage de la liste des sujets
            if ($result->rowCount() > 0) {
                while($row = $result->fetch(PDO::FETCH_ASSOC)) {
                    echo '<a href="sujet.php?id='.$row['id_sujet'].'" class="list-group-item list-group-item-action">';
                    echo '<h5 class="mb-1">'.$row['titre'].'</h5>';
                    echo '<small>'.$row['nom_utilisateur'].' - '.$row['date_creation'].' - Réponses: '.$row['nombre_reponses'].'</small>';
                    echo '</a>';
                }
            } else {
                echo "<p>Aucun sujet trouvé.</p>";
            }
            ?>
        </div>
    </div>
</div>

</body>
</html>