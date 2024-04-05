<?php
// Inclusion du fichier de connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

$stmt = $dbh->prepare("SELECT * FROM actualites ORDER BY date_publication DESC LIMIT 9");
$stmt->execute();
$actualites = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Balance Ton Bully - Accueil</title>
    <!-- Liens vers les polices et la feuille de style -->
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleCarousselIndex.css">
</head>
<body>
    <?php include('../includes/headerNav.php')?>
    <!-- Section pour afficher les statistiques -->
    <div class="container-stats">
        <div class="statistiques">
            <div class="first-stat">
                <p class="first-percent">10%</p>
                <p class="harassment">Des élèves de la primaire au lycée se sentent harcelés</p>
            </div>
            <div class="second-stat">
                <p class="second-percent">22%</p>
                <p class="high-school-harassment">Des élèves harcelés au lycée osent en parler</p>
            </div>
        </div>
        <div class="video-container">
            <iframe class="video" src="https://www.youtube.com/embed/EJVeyg5v2Xw"></iframe>
        </div>
    </div>
    <div class="transition mt-5 carousel-transition"></div>
    <h2 class="text-center mt-5 carousel-title" style="font-family: 'Roboto', sans-serif;">Les dernières actualités sur le harcèlement scolaire</h2>
    <div class="container carousel-container">
        <div id="carousel">
            <?php foreach ($actualites as $actu): ?>
                <figure>
                    <img src="<?php echo htmlspecialchars($actu['photo']); ?>" alt="Image d'actualité">
                    <figcaption>
                        <h3><?php echo htmlspecialchars($actu['titre']); ?></h3>
                        <a href="<?php echo htmlspecialchars($actu['lien_article']); ?>">Lire l'article</a>
                        <span><?php echo htmlspecialchars(date('d/m/Y', strtotime($actu['date_publication']))); ?></span>
                    </figcaption>
                </figure>
            <?php endforeach; ?>
        </div>
    </div>
    <br class="carousel-transition"><br class="carousel-transition"><br class="carousel-transition"><br class="carousel-transition">
    <div class="transition"></div>
    <div class="solutions">
        <div class="animated-element first-solution">
            <h4 class="title-first">Forums de témoignage</h4>
            <p class="content-first">Vous n’êtes pas coupable du harcèlement que vous subissez, osez demander de l’aide. Vous aurez accès à des témoignages qui montrent l’importance de ne pas se renfermer</p>
        </div>
        <div class="animated-element second-solution">
            <h4 class="title-second">Formations</h4>
            <p class="content-second">Nous proposons à ceux qui le souhaitent de se former afin de pouvoir devenir intervenant auprès des écoles </p>
        </div>
        <div class="animated-element illustrations">
            <img src="../ressources/stop-bullying-2.png" alt="illustration stop harcèlement">
        </div>
        <div class="animated-element third-solution">
            <h4 class="title-third">Interventions</h4>
            <p class="content-third">Nous sommes en capacité de vous proposer des sensibilisations dans vos écoles pour les élèves en classe  ou pour les enseignants qu’il puisse réagir à différents cas de harcèlement </p>
        </div>
        <div class="animated-element fourth-solution">
            <h4 class="title-fourth">Rendez-vous</h4>
            <p class="content-fourth">Des professionnels de la santé sociale nous rejoignent régulièrement sur notre plateforme afin de proposer aux jeunes victimes de harcèlement une aide concrète et un suivi personnalisé </p>
        </div>
    </div>
    <div class="transition"></div>
    <?php include('../includes/footer.php') ?>
    <?php include('../includes/scriptLink.php') ?>
    <!-- Script pour l'animation des pourcentages -->
    <script>
        $(document).ready(function() {
            // Fonction pour incrémenter le pourcentage progressivement
            function incrementerPourcentage($element, pourcentageCible) {
                var pourcentageActuel = 0; // Début à 0%
                var increment = 1; // Augmentation de 1% à chaque itération
                var interval = setInterval(function() {
                    pourcentageActuel += increment;
                    $element.text(pourcentageActuel + '%');
                    if (pourcentageActuel >= pourcentageCible) {
                        clearInterval(interval);
                        $element.text(pourcentageCible + '%'); // Assurez-vous que le pourcentage final est correct
                    }
                }, 70); // Vitesse de l'incrémentation (plus bas = plus rapide)
            }

            // Appel de la fonction pour chaque élément
            incrementerPourcentage($('.first-percent'), 10); // Première valeur cible: 10%
            incrementerPourcentage($('.second-percent'), 22); // Deuxième valeur cible: 22%
        });
    </script>
    <!-- Script pour l'animation du scroll trigger -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.9.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/ScrollMagic.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ScrollMagic/2.0.8/plugins/animation.gsap.min.js"></script>
    <script>
        // Initialiser ScrollMagic
        var controller = new ScrollMagic.Controller();

        // Créer une nouvelle timeline GSAP pour les animations séquentielles
        var timeline = gsap.timeline();

        // Sélectionnez toutes les divs à animer
        var elements = document.querySelectorAll('.animated-element');

        // Parcourez chaque élément et ajoutez-le à la timeline avec une animation fadeIn
        elements.forEach(function(element) {
            timeline.from(element, { opacity: 0, y: 50, duration: 0.9 }, "-=0.10"); // Décalage de 0.4 seconde entre chaque animation
        });

        // Créer une nouvelle scène pour déclencher l'animation lorsque la section "Nos solutions" devient visible
        var scene = new ScrollMagic.Scene({
            triggerElement: '.solutions',

            triggerHook: 0.9, // Déclenche l'animation lorsque 70% de la section est visible

            reverse: true // Ne pas inverser l'animation lorsque l'utilisateur fait défiler vers le haut
        })
        .setTween(timeline) // Utilisez la timeline GSAP comme tween
        .addTo(controller);

        // Pour le caroussel
        document.addEventListener("DOMContentLoaded", function() {
            var carousel = document.getElementById('carousel');
            var figures = carousel.getElementsByTagName('figure');
            var angle = 360 / figures.length;
            var radius = 350; // Rayon du cercle sur lequel les figures sont disposées

            Array.prototype.forEach.call(figures, function(figure, i) {
                figure.style.transform = `rotateY(${angle * i}deg) translateZ(${radius}px)`;
            });
        });



    </script>
</body>
</html>