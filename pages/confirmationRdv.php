<?php
// Inclure le fichier de fonctions et établir la connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Vérifier si les paramètres sont passés dans l'URL
if (isset($_GET['date_heure']) && isset($_GET['professionnel_id'])) {
    // Récupérer les valeurs des paramètres
    $date_heure = $_GET['date_heure'];
    $professionnel_id = $_GET['professionnel_id'];

    // Convertir la chaîne de caractères en objet DateTime
    $date_heure_obj = date_create_from_format('Y-m-d\TH:i:s', $_GET['date_heure']);

    // Vérifier si la conversion a réussi
    if ($date_heure_obj !== false) {
        // Formatter la date pour qu'elle soit au format DATETIME
        $date_heure_formattee = $date_heure_obj->format('Y-m-d H:i:s');

        // Vérifier les valeurs récupérées avec var_dump
        echo "Date et heure du rendez-vous : ";
        var_dump($date_heure_formattee);
        echo "<br>";
        echo "ID du professionnel : ";
        var_dump($professionnel_id);
        echo "<br>";

        // Récupérer les informations sur le professionnel de santé
        $stmt_pro = $dbh->prepare("SELECT * FROM professionnels_sante WHERE id = ?");
        $stmt_pro->execute([$professionnel_id]);
        $professionnel = $stmt_pro->fetch(PDO::FETCH_ASSOC);

        // Vérifier les informations sur le professionnel de santé avec var_dump
        echo "Informations sur le professionnel de santé : ";
        var_dump($professionnel);
        echo "<br>";

        // Vérifier si l'utilisateur est connecté
        if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
            // Récupérer les informations sur l'utilisateur
            $stmt_user = $dbh->prepare("SELECT * FROM utilisateurs WHERE id = ?");
            $stmt_user->execute([$_SESSION['id']]);
            $utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

            // Vérifier les informations sur l'utilisateur avec var_dump
            echo "Informations sur l'utilisateur : ";
            var_dump($utilisateur);
            echo "<br>";
        }
    } else {
        // La conversion de la date a échoué
        echo "Erreur : La date et l'heure ne sont pas au format valide.";
    }
} else {
    // Afficher un message d'erreur ou rediriger vers une autre page
    echo "Erreur : Les paramètres requis ne sont pas spécifiés.";
    exit(); // Arrêter l'exécution du script
}

// Vérifier si le formulaire de confirmation a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_rdv'])) {
    // Vérifier si la date et l'heure sont définies dans le formulaire
    if (isset($_POST['confirmDate'])) { // Assurez-vous que le nom du champ est correct ici
        // Convertir la chaîne de caractères en objet DateTime
        $date_heure_obj = date_create_from_format('Y-m-d\TH:i:s', $_POST['confirmDate']); // Assurez-vous que le nom du champ est correct ici

        // Vérifier si la conversion a réussi
        if ($date_heure_obj !== false) {
            // Formatter la date pour qu'elle soit au format DATETIME
            $date_heure_formattee = $date_heure_obj->format('Y-m-d H:i:s');

            // Récupérer les données du formulaire
            $professionnel_id = $professionnel['id']; // Utiliser l'ID du professionnel récupéré précédemment
            $utilisateur_id = $_SESSION['id'];
            $confirme = true; // Définir le champ confirme à true

            // Préparer la requête SQL pour insérer le rendez-vous dans la base de données
            $sql = "INSERT INTO rendez_vous (professionnel_id, utilisateur_id, date_heure, confirme) VALUES (?, ?, ?, ?)";
            $stmt = $dbh->prepare($sql);

            // Exécuter la requête avec les valeurs des paramètres
            $stmt->execute([$professionnel_id, $utilisateur_id, $date_heure_formattee, $confirme]);

            // Vérifier si l'insertion a réussi
            if ($stmt->rowCount() > 0) {
                // Le rendez-vous a été enregistré avec succès
                echo "<script>alert('Le rendez-vous a été enregistré avec succès dans la base de données.');</script>";
            } else {
                // Une erreur s'est produite lors de l'enregistrement du rendez-vous
                echo "<script>alert('Une erreur s\'est produite lors de l\'enregistrement du rendez-vous.');</script>";
            }
        } else {
            // La conversion de la date a échoué
            echo "<script>alert('Erreur : La date et l\'heure ne sont pas au format valide.');</script>";
        }
    } else if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // La date et l'heure ne sont pas définies dans le formulaire
        echo "<script>alert('Erreur : La date et l\'heure ne sont pas spécifiées dans le formulaire.');</script>";
    }
} else {
    echo "<script>alert('Erreur : Le formulaire de confirmation n\'a pas été soumis.');</script>";
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de rendez-vous</title>
    <!-- Liens vers les styles CSS -->
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="../css/styleContact.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&family=Roboto&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 blue-bg p-4 shadow-lg rounded">
            <h1 class="text-white">Confirmation de rendez-vous</h1>
            <p class="text-white">Veuillez confirmer votre rendez-vous en cliquant sur le bouton ci-dessous :</p>
            <!-- Affiche le message de succès si défini -->
            <?php if (!empty($successMessage)) echo $successMessage; ?>
            <!-- Affichage de la date du rendez-vous -->
            <p class="text-white">Date du rendez-vous : <?php echo isset($date_heure_formattee) ? $date_heure_formattee : ''; ?></p>
            <!-- Ajout du var_dump pour vérifier la valeur de $date_heure_formattee -->
            <?php var_dump($date_heure_formattee); ?>
            <div class="mb-3">
                <p class="text-white">Informations sur le professionnel de santé :</p>
                <ul class="text-white">
                    <li>Nom : <?php echo $professionnel['nom']; ?></li>
                    <li>Prénom : <?php echo $professionnel['prenom']; ?></li>
                    <li>Profession : <?php echo $professionnel['profession']; ?></li>
                    <li>Adresse : <?php echo $professionnel['adresse']; ?></li>
                    <li>Ville : <?php echo $professionnel['ville']; ?></li>
                    <li>Code Postal : <?php echo $professionnel['code_postal']; ?></li>
                </ul>
            </div>
            <?php if (isset($utilisateur) && !empty($utilisateur)) : ?>
                <div class="mb-3">
                    <p class="text-white">Informations sur l'utilisateur :</p>
                    <ul class="text-white">
                        <li>Nom : <?php echo $utilisateur['name']; ?></li>
                        <li>Prénom : <?php echo $utilisateur['firstName']; ?></li>
                        <li>Email : <?php echo $utilisateur['mail']; ?></li>
                    </ul>
                </div>
            <?php endif; ?>
            <form method="post" action="">
                <div class="row">
                    <!-- Champ caché pour stocker la date et l'heure -->
                    <input  name="confirmDate" value="<?php echo htmlspecialchars($date_heure_formattee ?? '', ENT_QUOTES); ?>">
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn btn-primary w-100" name="confirm_rdv">Confirmer</button>
                    </div>
                    <div class="col-md-6 mb-3">
                        <button type="submit" class="btn btn-danger w-100" formaction="../pages/rdv.php">Annuler</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<?php include('../includes/footer.php') ?>
</body>
</html>
