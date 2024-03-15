<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact</title>
  <link rel="stylesheet" href="../css/style.css">
</head>
<body>
  <link rel="stylesheet" href="../css/style.css">
  <h1>Contactez-nous</h1>
  <p>N'hésitez pas à nous contacter en utilisant le formulaire ci-dessous.</p>

  <form action="mailto:votreemail@exemple.com" method="post">
    <label for="name">Nom </label>
    <input type="text" id="name" name="name" placeholder="Votre nom">
    <br>

    <label for="email">Email </label>
    <input type="email" id="email" name="email" placeholder="votre@email.com">
    <br>

    <label for="Telephone">Téléphone </label>
    <input type="Telephone" id="phone" name="phone" placeholder="votre numero de Téléphone">
    <br>

    <label for="message">Message </label>
    <textarea id="message" name="message" rows="5" placeholder="Votre message..."></textarea>
    <br>

    <button type="submit">Envoyer</button>
    <button type="reset">Annuler</button>
  </form>

  <p>Vous pouvez également nous joindre par téléphone au : 01-XX-XX-XX-XX</p>

  <!-- Conteneur pour l'adresse et la carte -->
  <div class="address-container">
    <div class="address">
      <p>Notre adresse :</p>
      <p>30 rue Notre Dame des Victoires</p>
      <p>75002 Paris</p>
    </div>

 <!-- Conteneur pour la carte -->
 <div class="map-container">
  <div id="map"></div>
</div>

<!-- Script pour intégrer la carte Google Maps -->
<script>
  // Fonction d'initialisation de la carte
  function initMap() {
    // Coordonnées du point sur la carte
    var location = { lat: 48.8566, lng: 2.3522 }; // Exemple : Paris, France

    // Création de la carte
    var map = new google.maps.Map(document.getElementById("map"), {
      zoom: 12, // Zoom par défaut
      center: location // Centre de la carte
    });

    // Marqueur sur la carte
    var marker = new google.maps.Marker({
      position: location,
      map: map,
      title: "Notre emplacement"
    });
  }
</script>

<!-- Chargement de l'API Google Maps -->
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyABCTt9uLNjRLOX9NQqcThmUT5mPtW8p7A&callback=initMap"></script>
</body>
</html>