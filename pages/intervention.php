<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intervention</title>
    <style>
        .blue-bg {
            background-color: #0854C7;
        }
        
    </style>
    

</head>
<body>
  <?php include('../includes/headerNav.php')?>

<div class="container mt-4">


    <div class="my-5">

        <div class="text-center my-5">
            <img src="../ressources/stop-bullying.png" alt="Image centre" class="img-fluid mb-3" style="max-width: 100px;">
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
                        <label for="Nom de l’établissement" class="form-label text-white">Nom de l’établissement</label>
                        <input type="tel" class="form-control" id="Nom de l’établissement" name="Nom de l’établissement" placeholder="Nom de l’établissement" required>
                    </div>

                    <div class="my-4">
                        <label for="Numéro Siret" class="form-label text-white">Numéro Siret</label>
                        <input type="Numéro Siret" class="form-control" id="Numéro Siret" name="Numéro Siret" placeholder="Numéro Siret" required>
                    </div>

                    <div class="my-4">
                        <label for="Référent du projet" class="form-label text-white">Référent du projet</label>
                        <input type="Référent du projet" class="form-control" id="Référent du projet" name="Référent du projet" placeholder="Référent du projet" required>
                    </div>

                    <div class="my-4">
                        <label for="Date de l’intervention" class="form-label text-white">Date de l’intervention</label>
                        <input type="Date de l’intervention" class="form-control" id="Date de l’intervention" name="Date de l’intervention" placeholder="Date de l’intervention" required>
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
