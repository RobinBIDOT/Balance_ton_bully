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
    <title>Mentions Légales</title>
    <?php include('../includes/headLink.php') ?>
    <style>
        .custom-primary{
            color:#0854C7;
            font-size: 2.5rem;
        }
        .custom-secondary{
            color:#58C1F5;
            font-size: 2rem;
        }
        .text-justify {
            text-align: justify;
        }
    </style>
</head>
<body>
    <?php include('../includes/headerNav.php')?>
<div class="container my-5">
    <h1 class="custom-primary mb-5 text-center">Mentions Légales</h1>

    <section class="mb-5 ml-2">
        <h2 class="custom-secondary mb-2">Définitions</h2>
        <p class="text-justify">
            <strong>Client :</strong> Tout professionnel ou personne physique capable au sens des articles 1123 et suivants du Code civil, ou personne morale, qui visite le Site objet des présentes conditions générales.
        </p>
        <p class="text-justify">
        <strong>Prestations et Services :</strong> <a href="https://balancetonbully.org/">https://balancetonbully.org/</a> met à disposition des Clients :
        <ul class="text-justify">
            <li><strong>Contenu :</strong> Ensemble des éléments constituants l’information présente sur le Site, notamment textes, images, vidéos.</li>
            <li><strong>Informations clients :</strong> Ci-après dénommé « Information(s) » qui correspondent à l’ensemble des données personnelles susceptibles d’être détenues par <a href="https://balancetonbully.org/">https://balancetonbully.org/</a> pour la gestion de votre compte, de la gestion de la relation client et à des fins d’analyses et de statistiques.</li>
            <li><strong>Utilisateur :</strong> Internaute se connectant, utilisant le site susnommé.</li>
            <li><strong>Informations personnelles :</strong> « Les informations qui permettent, sous quelque forme que ce soit, directement ou non, l'identification des personnes physiques auxquelles elles s'appliquent » (article 4 de la loi n° 78-17 du 6 janvier 1978).</li>
        </ul>
        <p class="text-justify">
            Les termes « données à caractère personnel », « personne concernée », « sous-traitant » et « données sensibles » ont le sens défini par le Règlement Général sur la Protection des Données (RGPD : n° 2016-679).
        </p>
    </section>
   
    <section class="mb-5 ml-2">
        <h2 class="custom-secondary mb-2">1. Présentation du site internet</h2>
        <p class="text-justify">
            En vertu de l'article 6 de la loi n° 2004-575 du 9 avril 2024 pour la confiance dans l'économie numérique, il est précisé aux utilisateurs du site internet <a href="https://balancetonbully.org/">https://balancetonbully.org/</a> l'identité des différents intervenants dans le cadre de sa réalisation et de son suivi :
        </p>
        <ul class="text-justify">
            <li><strong>Propriétaire :</strong> Association Balance Ton Bully – 30 rue Notre Dame des Victoires 75002 Paris</li>
            <li><strong>Responsable publication :</strong> Walid Ben Aissa – 0138183818</li>
            <li><strong>Webmaster :</strong> XALOS – <a href="mailto:contact@xalos.digital">contact@xalos.digital</a></li>
            <li><strong>Hébergeur :</strong> 222 Bd Gustave Flaubert, 63000 Clermont-Ferrand - FRANCE</li>
            <li><strong>Délégué à la protection des données :</strong> Robin BIDOT – <a href="mailto:info@balancetonbully.org">info@balancetonbully.org</a></li>
        </ul>
        <p class="text-justify">
            Ces mentions légales RGPD sont issues du générateur gratuit de mentions légales pour un site internet.
        </p>
    </section>

    <section class="mb-5 ml-2">
        <h2 class="custom-secondary mb-2">2. Conditions générales d’utilisation du site et des services proposés</h2>
        <p class="text-justify">
            Ces mentions légales RGPD sont issues du générateur gratuit de mentions légales pour un site internet.
        </p>
        <p class="text-justify">
            Le Site constitue une œuvre de l’esprit protégée par les dispositions du Code de la Propriété Intellectuelle et des Réglementations Internationales applicables. Le Client ne peut en aucune manière réutiliser, céder ou exploiter pour son propre compte tout ou partie des éléments ou travaux du Site.
        </p>
    </section>

    <section class="mb-5 ml-2">
        <h2 class="custom-secondary mb-2">3. Description des services fournis</h2>
        <p class="text-justify">
            Le site internet <a href="https://balancetonbully.org/">https://balancetonbully.org/</a> a pour objet de fournir une information concernant l’ensemble des activités de la société. [https://balancetonbully.org/] s’efforce de fournir sur le site [https://balancetonbully.org/] des informations aussi précises que possible. Toutefois, il ne pourra être tenu responsable des oublis, des inexactitudes et des carences dans la mise à jour, qu’elles soient de son fait ou du fait des tiers partenaires qui lui fournissent ces informations.
        </p>
    </section>

    <section class="mb-5 ml-2">
        <h2 class="custom-secondary mb-2">4. Gestion des données personnelles</h2>
        <p class="text-justify">
            Le Client est informé des réglementations concernant la communication marketing, la loi du 21 Juin 2014 pour la confiance dans l’Economie Numérique, la Loi Informatique et Liberté du 06 Août 2004 ainsi que du Règlement Général sur la Protection des Données (RGPD : n° 2016-679).
        </p>
    </section>
</div>

    <?php include('../includes/footer.php') ?>
    <?php include('../includes/scriptLink.php') ?>
</body>
</html>