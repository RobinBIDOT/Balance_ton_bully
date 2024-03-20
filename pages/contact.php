<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
    <style>
        .blue-bg {
                background-color: #0854C7;
            }
    </style>
    
</head>
<body>
<?php include('../includes/headerNav.php') ?>

<!-- <div class="container mt-5">  -->
    <div class="d-flex my-5 justify-content-center align-items-center vh-100">
        <div class="col-md-6 col-lg-4 blue-bg p-4 shadow-lg rounded">
            <h1 class=" contact text-white">Contactez-nous</h1>
            <p class=" contact text-white">N'hésitez pas à nous contacter en utilisant le formulaire ci-dessous.</p>

            <form action="mailto:votreemail@exemple.com" method="post">
                <div class="mb-3">
                    <label for="name" class="form-label text-white">Nom </label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label text-white">Email </label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com">
                </div>

                <div class="mb-3">
                    <label for="Telephone" class="form-label text-white">Téléphone </label>
                    <input type="Telephone" id="phone" name="phone" class="form-control" placeholder="votre numero de Téléphone">
                </div>

                <div class="mb-3">
                    <label for="message" class="form-label text-white">Message </label>
                    <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message..."></textarea>
                </div>

                <button type="submit" class="btn btn-primary">Envoyer</button>
                <button type="reset" class="btn btn-secondary">Annuler</button>
            </form>

            <p class="mt-4 text-white">Vous pouvez également nous joindre par téléphone au : 01-32-43-98-22</p>

            <div class="row mt-5">
                <div class="col-md-6">
                    <div class="address text-white">
                        <h4>Notre adresse :</h4>
                        <p>30 rue Notre Dame des Victoires</p>
                        <p>75002 Paris</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div id="map" style="height: 100%;"></div>
                </div>
            </div>
        </div>
    </div>
<!-- </div>  -->


<script>
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
<!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>  -->
 <script>
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
<?php include('../includes/footer.php') ?>
</body>
</html>
