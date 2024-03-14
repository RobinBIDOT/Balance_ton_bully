
<?php

include('/Applications/MAMP/htdocs/Balance_ton_bully/php/tools/functions.php');
$dbh = dbConnexion();


function getActualites($dbh) {
    $stmt = $dbh->prepare("SELECT * FROM actualites ORDER BY date_publication DESC");
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}



$actualities = getActualites($dbh)

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
    <div class="container mt-4">
        <h2 class="mb-3 text-center">Toute l'actualité</h2>
        <?php $key = 0;?>
        <?php foreach ($actualities as $actu): ?>
            <div class="row no-gutters border rounded overflow-hidden mb-4 <?php echo $key % 2 == 0 ? '' : 'flex-md-row-reverse'; ?>">
           
                <div class="col-12 col-md-auto text-center">
                    <img src="<?php echo htmlspecialchars($actu['photo']); ?>" style="height: 250px;" alt="photo actu">
                </div>
                <div class="col p-4 d-flex flex-column position-static <?php echo $key % 2 == 0 ? '' : 'text-md-end'; ?>">
                    <h5 class="card-title"><?php echo htmlspecialchars($actu['titre']); ?></h5>
                    <p class="card-text"><?php echo htmlspecialchars($actu['contenu']); ?></p>
                    <p class="card-text"><small class="text-muted"><?php echo htmlspecialchars($actu['date_publication']); ?></small></p>
                </div>
                <div class="mt-auto <?php echo $key % 2 == 0 ? 'd-flex justify-content-end' : 'd-flex justify-content-start'; ?>">
                    <a href="<?php echo htmlspecialchars($actu['lien_article']); ?>" class="btn btn-link"><?php echo $key % 2 == 0 ? 'Voir l\'article...' : '...Voir l\'article'; ?></a>
                </div>
            </div>
        <?php endforeach; ?>
           
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>