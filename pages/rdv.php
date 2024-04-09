<?php
// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();


//// Vérifier si l'utilisateur est connecté
//if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
//    // Rediriger l'utilisateur vers la page de connexion
//    header('Location: ../pages/connexion.php');
//    exit();
//}

// Récupérer l'ID du professionnel depuis l'URL
if (isset($_GET['professionnel_id'])) {
    $professionnel_id = intval($_GET['professionnel_id']);
    echo '<span class="d-none">Professionnel ID : </span>';

//    var_dump($professionnel_id);
//    echo "<br><br>";
} else {
    // Rediriger vers une page d'erreur si l'ID n'est pas fourni
    header("Location: ../pages/consultations.php");
    exit();
}

// Initialiser la variable $date_en_cours avec la date d'aujourd'hui
$date_aujourdhui = date('Y-m-d');

// Calculer la date dans 1 semaine
$date_dans_1_semaine = date('Y-m-d', strtotime('+1 week'));

// Jours de la semaine en français
$jours_semaine = array('Dimanche', 'Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi');

// Initialiser un tableau pour stocker tous les événements
$evenements = array();

// Récupérer les horaires du professionnel depuis la base de données
$stmt = $dbh->prepare("SELECT * FROM horaires_professionnels WHERE professionnel_id = ?");
$stmt->execute([$professionnel_id]);
$horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Initialiser la variable $date_en_cours avec la date d'aujourd'hui
$date_en_cours = $date_aujourdhui;

// Pour chaque jour entre aujourd'hui et dans 90 jours
for ($i = 0; $i <= 90; $i++) { // Boucle sur les jours
    // Date en cours
    $date_en_cours = date('Y-m-d', strtotime($date_aujourdhui . " +$i day"));

    // Récupérer le jour de la semaine pour la date en cours
    $jour_semaine = date('N', strtotime($date_en_cours));
    $jour_semaine_fr = $jours_semaine[$jour_semaine - 1];

    // Vérifier si des horaires sont définis pour ce jour de la semaine
    foreach ($horaires as $horaire) {
        if ($horaire['jour_semaine'] == $jour_semaine_fr) {
            // Récupérer les heures de début et de fin pour le matin
            $heure_debut_matin = $horaire['heure_debut_matin'];
            $heure_fin_matin = $horaire['heure_fin_matin'];

            // Récupérer les heures de début et de fin pour l'après-midi
            $heure_debut_apres_midi = $horaire['heure_debut_apres_midi'];
            $heure_fin_apres_midi = $horaire['heure_fin_apres_midi'];

            // Définir la durée d'un rendez-vous
            $duree_rdv = $horaire['duree_rdv'];

            // Vérifier les créneaux horaires pour le matin
            verifierCreneaux($heure_debut_matin, $heure_fin_matin, $duree_rdv, $professionnel_id, $dbh, $evenements, $date_en_cours);

            // Vérifier les créneaux horaires pour l'après-midi
            verifierCreneaux($heure_debut_apres_midi, $heure_fin_apres_midi, $duree_rdv, $professionnel_id, $dbh, $evenements, $date_en_cours);
        }
    }
}


function verifierCreneaux($heure_debut, $heure_fin, $duree_rdv, $professionnel_id, $dbh, &$evenements, $date_en_cours) {
    // Vérifier si les heures de début et de fin sont définies
    if (!empty($heure_debut) && !empty($heure_fin)) {
        // Convertir les heures en timestamps
        $heure_debut_ts = strtotime($date_en_cours . " $heure_debut");
        $heure_fin_ts = strtotime($date_en_cours . " $heure_fin");

        // Tableau pour stocker les créneaux horaires déjà traités
        $creneaux_traites = array();

        // Ajouter les créneaux horaires
        while ($heure_debut_ts < $heure_fin_ts) {
            // Vérifier si un rendez-vous existe pour ce créneau horaire
            $date_heure_debut = date('Y-m-d H:i:s', $heure_debut_ts);
            $date_heure_fin = date('Y-m-d H:i:s', $heure_debut_ts + $duree_rdv * 60);
            $sql = "SELECT COUNT(*) AS count FROM rendez_vous WHERE professionnel_id = :professionnel_id AND date_heure >= :date_heure_debut AND date_heure < :date_heure_fin";
            $stmt = $dbh->prepare($sql);
            $stmt->bindParam(':professionnel_id', $professionnel_id, PDO::PARAM_INT);
            $stmt->bindParam(':date_heure_debut', $date_heure_debut);
            $stmt->bindParam(':date_heure_fin', $date_heure_fin);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            // Si aucun rendez-vous trouvé et le créneau n'a pas été ajouté, ajouter ce créneau horaire aux événements disponibles
            if ($result['count'] == 0 && !isset($creneaux_traites[$date_heure_debut])) {
                $evenements[] = array(
                    'title' => 'Disponible',
                    'start' => date('Y-m-d\TH:i:s', $heure_debut_ts),
                    'end' => date('Y-m-d\TH:i:s', $heure_debut_ts + $duree_rdv * 60),
                    'url' => 'confirmationRdv.php?professionnel_id=' . $professionnel_id . '&date_heure=' . date('Y-m-d\TH:i:s', $heure_debut_ts),
                    'backgroundColor' => '#28a745',
                    'borderColor' => '#28a745'
                );

                // Ajouter ce créneau horaire au tableau des créneaux traités
                $creneaux_traites[$date_heure_debut] = true;
            }

            // Avancer l'heure de début au prochain créneau horaire
            $heure_debut_ts += $duree_rdv * 60;
        }
    }
}

// Convertir le tableau des événements en format JSON pour l'utiliser dans le JavaScript
$evenements_json = json_encode($evenements);

//echo "Événements JSON : ";
//var_dump($evenements_json);
//echo "<br><br>";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <?php include('../includes/headLink.php') ?>
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js"></script>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container">
    <h1>Agenda de prise de rendez-vous</h1>
    <p>Sélectionnez un créneau horaire disponible pour prendre rendez-vous :</p>
    <div id="calendar"></div>
</div>
<?php include('../includes/footer.php') ?>
<?php include('../includes/scriptLink.php') ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: 'timeGridWeek,timeGridDay',
                center: 'title',
                right: 'prev,next'
            },
            navLinks: true,
            buttonText: {
                timeGridWeek: 'Semaine',
                timeGridDay: 'Jour',
            },
            height: '70%',
            dayHeaders: true,
            initialDate: "<?php echo $date_aujourdhui; ?>",
            editable: true,
            eventContent: function(arg) {
                return {
                    html: arg.event.title
                };
            },
            events: <?php echo $evenements_json; ?> // Charger les événements à partir du JSON
        });

        calendar.render();

    });
    // Gestionnaire d'événements pour le clic sur un créneau disponible
    calendar.on('dateClick', function(info) {
        // Récupérer la date et l'heure du clic
        var clickedDate = info.dateStr;
        // Récupérer l'ID du professionnel
        var professionnel_id = <?php echo $professionnel_id; ?>;
        // Rediriger vers la page de confirmation avec les paramètres appropriés
        window.location.href = 'confirmationRdv.php?date_heure=' + clickedDate + '&professionnel_id=' + professionnel_id;
    });
</script>
</body>
</html>