<?php
// Inclusion du fichier de connexion à la base de données
include('../php/tools/functions.php');
$dbConnect = dbConnexion(); // Connexion à la base de données

// Vérification de la session de l'utilisateur
session_start();
var_dump($_SESSION); // Dump de la session pour vérification

// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['userId'])) {
    // Redirection vers la page de connexion si l'utilisateur n'est pas connecté
    var_dump("Redirection vers la page de connexion. Les autorisations de l'utilisateur sont incorrectes.");
    header('Location: connexion.php');
    exit();
}

// Vérification des autorisations de l'utilisateur
if ($_SESSION['id_role'] !== 1) { // Vérifie si l'utilisateur n'est pas un administrateur
    // Redirection vers la page d'accueil si l'utilisateur n'est pas autorisé
    header('Location: index.php');
    exit();
}

// Vérification de la soumission du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $scheduleId = $_POST['scheduleId'];
    $newStartTimeMorning = $_POST['newStartTime'];
    $newEndTimeAfternoon = $_POST['newEndTime'];
    $newDuration = $_POST['dureeRdv'];

    // Validation des données
    if(empty($newStartTimeMorning) || empty($newEndTimeAfternoon) || empty($newDuration)) {
        // Si l'un des champs est vide, afficher un message d'erreur et arrêter le traitement
        header('Location: modifierHoraires.php?error=Veuillez remplir tous les champs.');
        exit();
    }

    // Mise à jour des horaires dans la base de données
    $stmt = $dbConnect->prepare('UPDATE horaires_professionnels SET heure_debut_matin = ?, heure_fin_apres_midi = ?, duree_rdv = ? WHERE id = ?');
    $stmt->execute([$newStartTimeMorning, $newEndTimeAfternoon, $newDuration, $scheduleId]);

    // Redirection vers la page du profil professionnel avec un message de succès
    header('Location: profilProfessionnel.php?message=Horaires mis à jour avec succès');
    exit();
} else {
    // Redirection vers la page d'accueil si le formulaire n'a pas été soumis
    header('Location: index.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier les horaires</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/styleDons.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container mt-5">
    <h2 class="mb-4">Modifier les horaires</h2>
    <!-- Formulaire pour modifier les horaires -->
    <form action="modifierHoraires.php" method="POST">
        <input type="hidden" name="scheduleId" value="<?php echo $_POST['scheduleId']; ?>">
        <div class="form-group">
            <label for="newStartTime">Heure de début (matin) :</label>
            <input type="time" class="form-control" id="newStartTime" name="newStartTime" required>
        </div>
        <div class="form-group">
            <label for="newEndTime">Heure de fin (après-midi) :</label>
            <input type="time" class="form-control" id="newEndTime" name="newEndTime" required>
        </div>
        <div class="form-group">
            <label for="dureeRdv">Durée des rendez-vous :</label>
            <select class="form-control" id="dureeRdv" name="dureeRdv" required>
                <option value="30">30 minutes</option>
                <option value="60">60 minutes</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Enregistrer</button>
    </form>
</div>
<?php include('../includes/footer.php') ?>
</body>
</html>