
<?php
session_start()
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Qui Sommes-Nous ?</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">

    <style>
        body {font-family: 'Roboto', sans-serif;background-color: #f8f9fa;}
        h1, h2 {font-family: 'Montserrat', sans-serif;margin-bottom: 1rem;}
        p, li {font-size: 1rem;line-height: 1.5;}
        .custom-primary { color: #0854C7; }
        .custom-secondary { color: #58C1F5; }
        .flipping-card { perspective: 1000px; }
        .flipping-card-inner { position: relative; width: 100%; height: 100%; text-align: center; transition: transform 0.8s; transform-style: preserve-3d; }
        .flipping-card:hover .flipping-card-inner { transform: rotateY(180deg); }
        .flipping-card-front, .flipping-card-back { position: absolute; width: 100%; height: 100%; backface-visibility: hidden; }
        .flipping-card-front { background-color: #bbb; color: black; }
        .flipping-card-back { color:#0854C7; transform: rotateY(180deg); }
        .team-member-image { max-height: 200px; /* Ajustez la hauteur maximale selon vos besoins */ }
        .team-member-description { margin-top: 10px; font-size: 1rem;} /* Ajoute une marge supérieure à la description */
    </style>
</head>
<body>
    <?php include('../includes/headerNav.php'); ?>

    <div class="container my-5 vh-100">
        <h1 class="custom-primary mb-5 text-center custom-primary">Qui Sommes-Nous ?</h1>

        <section class="ml-2">
            <h2 class="custom-secondary">Notre Mission</h2>
            <p>Balance ton Bully est bien plus qu'un simple service en ligne. Nous sommes une association reconnue d'utilité publique depuis 2024, engagée corps et âme dans la protection des enfants, des adolescents, et des parents. Notre mission principale est la prévention et la sensibilisation. Chaque année, nous touchons 200 000 personnes à travers nos actions menées dans les écoles, les collèges, les lycées, et au sein des familles et des milieux professionnels. Nous sommes agréés par le Ministère de l’Éducation nationale et de la Jeunesse, ce qui témoigne de notre engagement et de notre crédibilité dans ce domaine.</p>

            <h2 class="custom-secondary">Notre Engagement</h2>
            <p>Nous nous engageons à offrir un service d'intervention et de support actif. Notre équipe d'écoutants qualifiés, en lien avec des psychologues et des spécialistes du numérique, est disponible 7 jours sur 7 jusqu’à 23h pour accompagner, conseiller et guider les victimes, les parents et les professionnels dans la gestion des situations de harcèlement et de violences en ligne.</p>

            <h2 class="custom-secondary">Notre Slogan</h2>
            <p>“Personne ne devrait avoir peur d’aller à l’école." Ce slogan incarne notre vision d'un monde où le harcèlement n'a pas sa place, où chaque individu se sent soutenu et écouté. Ensemble, nous pouvons mettre fin au silence et construire un environnement plus sûr et bienveillant pour tous. Rejoignez-nous dans notre combat contre le harcèlement en milieu scolaire. Ensemble, nous sommes plus forts.</p>

            <h2 class="custom-secondary">Nos Moyens d’Action</h2>
            <ul>
                <li><strong>Sensibilisation et Prévention :</strong> Nous intervenons dans les écoles, les collèges, les lycées et auprès des professionnels pour sensibiliser et éduquer sur les risques du harcèlement et des comportements irrespectueux en ligne. Nos programmes de sensibilisation sont agréés par le Ministère de l’Éducation nationale et de la Jeunesse.</li>
                <li><strong>Formation :</strong> Nous formons les parents, les professionnels de l'éducation, ainsi que nos pairs et partenaires sur les bonnes pratiques et les outils nécessaires pour prévenir et gérer les situations de harcèlement.</li>
                <li><strong>Accompagnement Personnalisé :</strong> Nos écoutants, composés de spécialistes de la santé sociale, fournissent un soutien personnalisé et des conseils adaptés à chaque situation.</li>
                <li><strong>Forum d’échange :</strong> Nous mettons en place un lieu d'échange de témoignages qui permet aux jeunes victimes d'harcèlement de réaliser qu'ils ont le droit de s'exprimer et de demander de l'aide.</li>
            </ul>
        </section>
        <section class=" ml-2">
            <h2 class="custom-secondary">Une Équipe de Direction Expérimentée</h2>
            <div class="row mt-4">
                <div class="col-md-3 mb-4">
                    <div class="flipping-card">
                        <div class="flipping-card-inner">
                            <div class="flipping-card-front">
                                <img src="../ressources/Kevin.jpg" class="img-fluid team-member-image rounded-2" alt="Membre de l'équipe">
                            </div>
                            <div class="flipping-card-back">
                                <h3 class="team-member-name">Kevin</h3>
                                <p class="team-member-description">Chef de Projet</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="flipping-card">
                        <div class="flipping-card-inner">
                            <div class="flipping-card-front">
                                <img src="../ressources/Walid.jpg" class="img-fluid team-member-image rounded-2" alt="Membre de l'équipe">
                            </div>
                            <div class="flipping-card-back">
                                <h3 class="team-member-name">Walid</h3>
                                <p class="team-member-description">Front-End</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="flipping-card">
                        <div class="flipping-card-inner">
                            <div class="flipping-card-front">
                                <img src="../ressources/Robin.jpg" class="img-fluid team-member-image rounded-2" alt="Membre de l'équipe">
                            </div>
                            <div class="flipping-card-back">
                                <h3 class="team-member-name">Robin</h3>
                                <p class="team-member-description">Back-End</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="flipping-card">
                        <div class="flipping-card-inner">
                            <div class="flipping-card-front">
                                <img src="../ressources/Godwill.jpg" class="img-fluid team-member-image rounded-2" alt="Membre de l'équipe">
                            </div>
                            <div class="flipping-card-back">
                                <h3 class="team-member-name">Godwill</h3>
                                <p class="team-member-description">Front-End</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>

    <?php include('../includes/footer.php'); ?>
</body>
</html>
