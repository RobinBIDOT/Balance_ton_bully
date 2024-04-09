
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
    <title>Qui Sommes-Nous ?</title>
    <?php include('../includes/headLink.php') ?>
    <style>
        body {font-family: 'Roboto', sans-serif;background-color: #f8f9fa;}
        h1, h2, h5 {font-family: 'Montserrat', sans-serif;margin-bottom: 1rem;}
        p, li {font-size: 1rem;line-height: 1.5;}
        .custom-primary{
            color:#0854C7;
            font-size: 2.5rem;
        }
        .custom-secondary{
            color:#58C1F5;
            font-size: 2rem;
            margin-top: 2em;
            margin-bottom: 2em;
        }
        .custom-primaryp{
            color:#0854C7;
            margin-top: 2em;
            margin-bottom: 2em;
        }

        .text-justify {
            text-align: justify;
        }
        .card-img-top {width: 100%; height: 420px; object-fit: cover; border-radius: 0.25rem;}
        .card{transition: transform 0.3s ease-in-out; }
        .card:hover {transform: scale(1.05);}

    </style>
</head>
<body>
    <?php include('../includes/headerNav.php'); ?>

    <div class="container my-5">
        <h1 class="custom-primary mb-5 text-center">Qui Sommes-Nous ?</h1>

        <section>
            <h2 class="custom-secondary mb-2">Notre Mission</h2>
            <p class="text-justify">Balance ton Bully est bien plus qu'un simple service en ligne. Nous sommes une association reconnue d'utilité publique depuis 2024, engagée corps et âme dans la protection des enfants, des adolescents, et des parents. Notre mission principale est la prévention et la sensibilisation. Chaque année, nous touchons 200 000 personnes à travers nos actions menées dans les écoles, les collèges, les lycées, et au sein des familles et des milieux professionnels. Nous sommes agréés par le Ministère de l’Éducation nationale et de la Jeunesse, ce qui témoigne de notre engagement et de notre crédibilité dans ce domaine.</p>
        </section>

        <section>
            <h2 class="custom-secondary mb-2">Notre Engagement</h2>
            <p class="text-justify">Nous nous engageons à offrir un service d'intervention et de support actif. Notre équipe d'écoutants qualifiés, en lien avec des psychologues et des spécialistes du numérique, est disponible 7 jours sur 7 jusqu’à 23h pour accompagner, conseiller et guider les victimes, les parents et les professionnels dans la gestion des situations de harcèlement et de violences en ligne.</p>
        </section>

        <section>
            <h2 class="custom-secondary mb-2">Notre Slogan</h2>
            <p class="text-justify">“Personne ne devrait avoir peur d’aller à l’école." Ce slogan incarne notre vision d'un monde où le harcèlement n'a pas sa place, où chaque individu se sent soutenu et écouté. Ensemble, nous pouvons mettre fin au silence et construire un environnement plus sûr et bienveillant pour tous. Rejoignez-nous dans notre combat contre le harcèlement en milieu scolaire. Ensemble, nous sommes plus forts.</p>
        </section>

        <section>
            <h2 class="custom-secondary mb-2">Nos Moyens d’Action</h2>
            <ul class="text-justify">
                <li><strong>Sensibilisation et Prévention :</strong> Nous intervenons dans les écoles, les collèges, les lycées et auprès des professionnels pour sensibiliser et éduquer sur les risques du harcèlement et des comportements irrespectueux en ligne. Nos programmes de sensibilisation sont agréés par le Ministère de l’Éducation nationale et de la Jeunesse.</li>
                <li><strong>Formation :</strong> Nous formons les parents, les professionnels de l'éducation, ainsi que nos pairs et partenaires sur les bonnes pratiques et les outils nécessaires pour prévenir et gérer les situations de harcèlement.</li>
                <li><strong>Accompagnement Personnalisé :</strong> Nos écoutants, composés de spécialistes de la santé sociale, fournissent un soutien personnalisé et des conseils adaptés à chaque situation.</li>
                <li><strong>Forum d’échange :</strong> Nous mettons en place un lieu d'échange de témoignages qui permet aux jeunes victimes d'harcèlement de réaliser qu'ils ont le droit de s'exprimer et de demander de l'aide.</li>
            </ul>
        </section>
        <section>
            <h2 class="custom-secondary mb-2">Une Équipe de Direction Expérimentée</h2>
            <div class="row my-5">
                <div class="col-6 col-sm-6 col-lg-3 mb-4">
                    <div class="card">
                        <img src="../ressources/Kevin.jpg" class="card-img-top img-fluid rounded-2" alt="Kevin">
                        <div class="card-body">
                            <h5 class="cart-title custom-primaryp">Kevin</h5>
                            <p class="card-text custom-primaryp">Chef de Projet</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-lg-3 mb-4">
                    <div class="card">
                        <img src="../ressources/Walid.jpg" class="card-img-top img-fluid rounded-2" alt="Walid">
                        <div class="card-body">
                            <h5 class="cart-title custom-primaryp">Walid</h5>
                            <p class="card-text custom-primaryp">Front-End</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-lg-3 mb-4">
                    <div class="card">
                        <img src="../ressources/Robin.jpg" class="card-img-top img-fluid rounded-2" alt="Robin">
                        <div class="card-body">
                            <h5 class="cart-title custom-primaryp">Robin</h5>
                            <p class="card-text custom-primaryp">Back-End</p>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-sm-6 col-lg-3 mb-4">
                    <div class="card">
                        <img src="../ressources/Godwill.jpg" class="card-img-top img-fluid rounded-2" alt="Godwill">
                        <div class="card-body">
                            <h5 class="cart-title custom-primaryp">Godwill</h5>
                            <p class="card-text custom-primaryp">Front-End</p>
                        </div>
                    </div>
                </div>
            </div>

        </section>
    </div>

    <?php include('../includes/footer.php'); ?>
    <?php include('../includes/scriptLink.php') ?>
</body>
</html>