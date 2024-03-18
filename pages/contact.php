<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact</title>
    <link rel="stylesheet" href="../css/style.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>

<div class="container mt-5">
    <h1 class="mb-4">Contactez-nous</h1>
    <p>N'hésitez pas à nous contacter en utilisant le formulaire ci-dessous.</p>

    <form action="mailto:votreemail@exemple.com" method="post">
        <div class="mb-3">
            <label for="name" class="form-label">Nom </label>
            <input type="text" id="name" name="name" class="form-control" placeholder="Votre nom">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email </label>
            <input type="email" id="email" name="email" class="form-control" placeholder="votre@email.com">
        </div>

        <div class="mb-3">
            <label for="Telephone" class="form-label">Téléphone </label>
            <input type="Telephone" id="phone" name="phone" class="form-control" placeholder="votre numero de Téléphone">
        </div>

        <div class="mb-3">
            <label for="message" class="form-label">Message </label>
            <textarea id="message" name="message" rows="5" class="form-control" placeholder="Votre message..."></textarea>
        </div>

        <button type="submit" class="btn btn-primary">Envoyer</button>
        <button type="reset" class="btn btn-secondary">Annuler</button>
    </form>

    <p class="mt-4">Vous pouvez également nous joindre par téléphone au : 01-XX-XX-XX-XX</p>

    <div class="row mt-5">
        <div class="col-md-6">
            <div class="address">
                <h4>Notre adresse :</h4>
                <p>30 rue Notre Dame des Victoires</p>
                <p>75002 Paris</p>
            </div>
        </div>

        <div class="col-md-6">
            <div id="map" style="height: 250px;"></div>
        </div>
    </div>
</div>

<?php include('../includes/footer.php') ?>
<!-- Chargement de l'API Google Maps -->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABCTt9uLNjRLOX9NQqcThmUT5mPtW8p7A&callback=initMap"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
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
</body>
</html>
