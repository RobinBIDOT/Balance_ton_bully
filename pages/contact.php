<?php
/**
 * Script de traitement du formulaire de contact.
 *
 * Ce script gère la réception des données du formulaire de contact,
 * les insère dans la base de données et affiche un message de succès.
 *
 */

// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérification si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Collecte et nettoyage des données du formulaire
    $prenom = $_POST['prenom'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $email = $_POST['email'] ?? '';
    $telephone = $_POST['phone'] ?? '';
    $message = $_POST['message'] ?? '';

    // Préparation et exécution de la requête SQL
    $query = $dbh->prepare("INSERT INTO messages_contact (prenom, nom, mail, telephone, message) VALUES (?, ?, ?, ?, ?)");
    $query->execute([$prenom, $nom, $email, $telephone, $message]);

    // Stocke un message de succès à afficher plus tard
    $successMessage = '<div class="alert alert-success">Message envoyé avec succès.</div>';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleContact.css">
</head>
<body>
<?php include('../includes/headerNav.php'); ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 blue-bg p-4 shadow-lg rounded">
            <h1 class="text-white">Contactez-nous</h1>
            <p class="text-white">N'hésitez pas à nous contacter en utilisant le formulaire ci-dessous.</p>
            <!-- Affiche le message de succès si défini -->
            <?php if (!empty($successMessage)) echo $successMessage; ?>
            <form action="contact.php" method="post">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="prenom" class="form-label text-white">Prénom</label>
                        <input type="text" id="prenom" name="prenom" class="form-control" placeholder="Votre prénom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nom" class="form-label text-white">Nom</label>
                        <input type="text" id="nom" name="nom" class="form-control" placeholder="Votre nom" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label text-white">Email</label>
                        <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="phone" class="form-label text-white">Téléphone</label>
                        <input type="tel" id="phone" name="phone" class="form-control" placeholder="Votre numéro de téléphone" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="message" class="form-label text-white">Message</label>
                    <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message..." required></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Envoyer</button>
            </form>
            <div class="address text-white mt-4">
                <h4>Notre adresse :</h4>
                <p>30 rue Notre Dame des Victoires<br>75002 Paris<br>Vous pouvez également nous joindre par téléphone au : 01-32-43-98-22</p>
            </div>
        </div>
    </div>
</div>
<!-- Conteneur pour la carte Google Maps -->
<div class="map-container my-5 mx-auto" style="width: 90%; max-width: 1600px;">
    <div id="map" style="height: 600px;" class="custom-map"></div>
</div>
<script>
    /**
     * Initialise la carte Google Maps.
     *
     * Cette fonction crée une carte Google Maps avec un marqueur
     * indiquant l'emplacement spécifié.
     */
    function initMap() {
        var location = { lat: 48.8566, lng: 2.3522 };
        var map = new google.maps.Map(document.getElementById("map"), {
            zoom: 12,
            center: location
        });
        var marker = new google.maps.Marker({
            position: location,
            map: map,
            title: "Notre emplacement"
        });
    }
</script>
<!-- Chargement de l'API Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABCTt9uLNjRLOX9NQqcThmUT5mPtW8p7A&callback=initMap"></script>
<?php include('../includes/footer.php'); ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>