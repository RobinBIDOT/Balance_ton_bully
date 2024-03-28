<?php
// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Demande d'intervention contre le harcèlement scolaire</title>
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
        .form-label {
            color: #ffffff;
        }
    </style>
</head>
<body>
  <?php include('../includes/headerNav.php')?>
  <div class="container mt-4">
      <div class="my-5">
          <div class="text-center my-5">
              <img src="../assets/stopBullying.webp" alt="Image centre" class="img-fluid mb-3" style="max-width: 100px;">
              <h1 class="titre-intervention">Intervention</h1>
          </div>
          <p class="intervention-contenu my-5">La lutte contre le harcèlement scolaire est une démarche essentielle qui forme le socle d'un environnement éducatif sain et propice au développement de chaque élève. Le harcèlement, par ses répercussions dévastatrices, peut altérer la trajectoire de vie des jeunes, entravant leur réussite scolaire, leur bien-être émotionnel et social.<br><br>
              Notre service propose des interventions par des professionnels expérimentés qui mettent en lumière l'urgence et les méthodes de prévention du harcèlement à l'école. En cultivant la sensibilisation et l'empathie, nos intervenants visent à instaurer une culture de respect et de solidarité au sein des établissements.<br><br>
              Ils outillent les élèves, enseignants et parents avec des stratégies concrètes pour détecter et agir face aux cas de harcèlement, contribuant ainsi à un climat scolaire où la sécurité et la confiance sont primordiales.<br><br>
              Faire de nos écoles des lieux sécurisés est notre priorité, car éduquer nos jeunes dans un environnement bienveillant est la promesse d'une société future plus respectueuse et inclusive. C'est pourquoi chaque action entreprise contre le harcèlement est un pas vers un avenir où chaque enfant peut apprendre et grandir librement, sans crainte d'intimidation ou d'isolement.
          </p>
          <div class="d-flex justify-content-center align-items-center vh-90">
              <div class="col-md-6 col-lg-4 blue-bg p-4 shadow-lg rounded">
                  <h2 class="text-center text-white mb-2">Demande d'intervention</h2>
                  <form action="#" method="post" class="mt-4 intervention">
                      <div class="my-4">
                          <label for="nom_etablissement" class="form-label">Nom de l’établissement</label>
                          <input type="text" class="form-control" id="nom_etablissement" name="nom_etablissement" placeholder="Nom de l’établissement" required>
                      </div>
                      <div class="my-4">
                          <label for="numero_siret" class="form-label">Numéro Siret</label>
                          <input type="text" class="form-control" id="numero_siret" name="numero_siret" placeholder="Numéro Siret" required>
                      </div>
                      <div class="my-4">
                          <label for="referent_projet" class="form-label">Référent du projet</label>
                          <input type="text" class="form-control" id="referent_projet" name="referent_projet" placeholder="Référent du projet" required>
                      </div>
                      <div class="my-4">
                          <label for="date_intervention" class="form-label">Date de l’intervention</label>
                          <input type="date" class="form-control" id="date_intervention" name="date_intervention" placeholder="Date de l’intervention" required>
                      </div>
                      <button type="submit" class="btn btn-primary text-white">Envoyer</button>
                  </form>
              </div>
          </div>
      </div>
  </div>
  <?php include('../includes/footer.php') ?>
</body>
</html>