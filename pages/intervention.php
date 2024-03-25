<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intervention</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/style-intervention.css">

</head>
<body>
  <?php include('../includes/headerNav.php')?>

  <div class="container mt-4">
      <div class="row">
        <div class="d-flex justify-content-between align-items-center">

                <div class="d-flex align-items-center">
                    <img src="../ressources/stop-bullying.png" alt="Image centre" class="img-fluid" style="max-width: 100px;">
                </div>
                <h1 class="flex-grow-1 text-center titre-intervention">Intervention</h1>
                </div>

            </div>
        </div>
     

      <p class="intervention-contenu">La lutte contre le harcèlement scolaire est une démarche essentielle qui forme le socle d'un environnement éducatif sain et propice au développement de chaque élève. Le harcèlement, par ses répercussions dévastatrices, peut altérer la trajectoire de vie des jeunes, entravant leur réussite scolaire, leur bien-être émotionnel et social.<br><br>
        Notre service propose des interventions par des professionnels expérimentés qui mettent en lumière l'urgence et les méthodes de prévention du harcèlement à l'école. En cultivant la sensibilisation et l'empathie, nos intervenants visent à instaurer une culture de respect et de solidarité au sein des établissements.<br><br>
        Ils outillent les élèves, enseignants et parents avec des stratégies concrètes pour détecter et agir face aux cas de harcèlement, contribuant ainsi à un climat scolaire où la sécurité et la confiance sont primordiales.<br><br>
        Faire de nos écoles des lieux sécurisés est notre priorité, car éduquer nos jeunes dans un environnement bienveillant est la promesse d'une société future plus respectueuse et inclusive. C'est pourquoi chaque action entreprise contre le harcèlement est un pas vers un avenir où chaque enfant peut apprendre et grandir librement, sans crainte d'intimidation ou d'isolement.</p>   
      <form action="#" method="post" class="mt-4 intervention">
          <div class="mb-3">
              <label for="Nom de l’établissement" class="form-label">Nom de l’établissement</label>
              <input type="tel" class="form-control" id="Nom de l’établissement" name="Nom de l’établissement" placeholder="Nom de l’établissement" required>
          </div>

          <div class="mb-3">
              <label for="Numéro Siret" class="form-label">Numéro Siret</label>
              <input type="Numéro Siret" class="form-control" id="Numéro Siret" name="Numéro Siret" placeholder="Numéro Siret" required>
          </div>

          <div class="mb-3">
              <label for="Référent du projet" class="form-label">Référent du projet</label>
              <input type="Référent du projet" class="Référent du projet" id="Référent du projet" name="Référent du projet" placeholder="Référent du projet" required>
          </div>

          <div class="mb-3">
              <label for="Date de l’intervention" class="form-label">Date de l’intervention</label>
              <input type="Date de l’intervention" class="form-control" id="Date de l’intervention" name="Date de l’intervention" placeholder="Date de l’intervention" required>
          </div>

          
          <button type="submit" class="btn btn-primary">Envoyer</button>
      </form>
    </div>
  <?php include('../includes/footer.php') ?>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.0.0-alpha1/js/bootstrap.bundle.min.js"></script> 
</body>
</html>
