<?php
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page en Construction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <!-- Ajoutez vos propres styles ici -->
    <style>
        /* Style spécifique à la page en construction */
        .construction-container {
            margin-top: 50px;
        }
        .construction-info {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<?php include('../includes/headerNav.php') ?>
<body>
<div class="container construction-container">
    <h1 class="text-center mb-4">Page en Construction</h1>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="construction-info">
                <p>Cette page est en construction. Merci de revenir plus tard.</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
