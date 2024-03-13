<?php
include('tools/functions.php');
$dbConnect = dbConnexion();
session_start();




?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
    <title>Balance Ton Bully - Accueil</title>
</head>
<body>
    <?php if (isset($_SESSION['nickName'])){ ?>
       <h1>bienvenue <?php echo $_SESSION['nickName'] ?></h1>
    <?php } ?>

    <div class="container-stats">
        <div class="statistiques">
            <div class="first-stat">
                <p class="first-percent">~10%</p>
                <p class="harassment">Des élèves de la primaire au lycée se sentent harcelés</p>
            </div>
            <div class="second-stat">
                <p class="second-percent">~22%</p>
                <p class="high-school-harassment">Des élèves harcelés au lycées osent en parler</p>
            </div>
        </div>
        <div class="video-container">
            <iframe class="video" src="https://www.youtube.com/embed/EJVeyg5v2Xw"></iframe>
        </div>
    </div>
    <div class="transition"></div>
    <div class="solutions">
        <div class="first-solution">
            <h4 class="title-first">Forums de témoignage</h4>
            <p class="content-first">Vous n’êtes pas coupable du harcèlement que vous subissez, osez demander de l’aide. Vous aurez accès à des témoignages qui montrent l’importance de ne pas se renfermer</p>
        </div>
        <div class="second-solution">
            <h4 class="title-second">Formations</h4>
            <p class="content-second">Nous proposons a ceux qui le souhaitent de se former afin de pouvoir devenir intervenant auprès des écoles </p>
        </div>
        <div class="illustrations">
            <img src="../ressources/stop-bullying-2.png" alt="illustration stop harcèlement">
        </div>
        <div class="third-solution">
            <h4 class="title-third">Interventions</h4>
            <p class="content-third">Nous sommes en capacité de vous proposer des sensibilisations dans vos écoles pour les élèves en classe  ou pour les enseignants qu’il puisse réagir a differents cas de hacèlement </p>
        </div>
        <div class="fourth-solution">
            <h4 class="title-fourth">Rendez-vous</h4>
            <p class="content-fourth">Des professionnels de la santé sociale nous rejoigne régulièrement sur notre plateforme afin de proposer au jeunes victimes de harcèlement une aide concrète et un suivi personnalisé </p>
        </div>
    </div>
    <div class="transition"></div>
</body>
</html>


