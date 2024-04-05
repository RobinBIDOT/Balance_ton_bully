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

        // Vérifier si le rendez-vous existe déjà
        $stmt_check = $dbh->prepare("SELECT COUNT(*) AS count FROM rendez_vous WHERE professionnel_id = ? AND date_heure = ?");
        $stmt_check->execute([$professionnel_id, $date_heure_formattee]);
        $result = $stmt_check->fetch(PDO::FETCH_ASSOC);

        // Si aucun rendez-vous trouvé, insérer le nouveau rendez-vous
        if ($result['count'] == 0) {
            // Récupérer les informations sur le professionnel de santé
            $stmt_pro = $dbh->prepare("SELECT * FROM professionnels_sante WHERE id = ?");
            $stmt_pro->execute([$professionnel_id]);
            $professionnel = $stmt_pro->fetch(PDO::FETCH_ASSOC);

            // Vérifier si l'utilisateur est connecté
            if (isset($_SESSION['id']) && !empty($_SESSION['id'])) {
                // Récupérer les informations sur l'utilisateur
                $stmt_user = $dbh->prepare("SELECT * FROM utilisateurs WHERE id = ?");
                $stmt_user->execute([$_SESSION['id']]);
                $utilisateur = $stmt_user->fetch(PDO::FETCH_ASSOC);

                // Vérifier si les informations sur le professionnel et l'utilisateur sont présentes
                if ($professionnel && $utilisateur) {
                    // Insérer le rendez-vous dans la base de données
                    $utilisateur_id = $_SESSION['id'];
                    $confirme = true;

                    // Préparer la requête SQL pour insérer le rendez-vous dans la base de données
                    $sql = "INSERT INTO rendez_vous (professionnel_id, utilisateur_id, date_heure, confirme) VALUES (?, ?, ?, ?)";
                    $stmt = $dbh->prepare($sql);
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
                    // Informations manquantes sur le professionnel ou l'utilisateur
                    echo "<script>alert('Erreur : Informations manquantes sur le professionnel ou l\'utilisateur.');</script>";
                }
            } else {
                // L'utilisateur n'est pas connecté
                echo "<script>alert('Erreur : L\'utilisateur n\'est pas connecté.');</script>";
            }
        } else {
            // Le rendez-vous existe déjà
            echo "<script>alert('Un rendez-vous existe déjà pour ce professionnel à cette date et heure.');</script>";
        }
    } else {
        // La conversion de la date a échoué
        echo "<script>alert('Erreur : La date et l\'heure ne sont pas au format valide.');</script>";
    }
} else {
    // Afficher un message d'erreur ou rediriger vers une autre page
    echo "Erreur : Les paramètres requis ne sont pas spécifiés.";
    exit(); // Arrêter l'exécution du script
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de rendez-vous</title>
    <!-- Liens vers les styles CSS -->
    <?php include('../includes/headLink.php') ?>
    <link rel="stylesheet" href="../css/styleContact.css">
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 blue-bg p-4 shadow-lg rounded">
            <h1 class="text-white">Confirmation de votre rendez-vous</h1>
            <!-- Affiche le message de succès si défini -->
            <?php if (!empty($successMessage)) echo $successMessage; ?>
            <!-- Affichage de la date du rendez-vous -->
            <p class="text-white">Date du rendez-vous : <?php echo isset($date_heure_formattee) ? $date_heure_formattee : ''; ?></p>
            <!-- Ajout du var_dump pour vérifier la valeur de $date_heure_formattee -->
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
        </div>
    </div>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
</body>
</html>
