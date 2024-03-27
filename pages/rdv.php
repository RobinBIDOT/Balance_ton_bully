<?php
// Activation de l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclusion des fonctions et connexion à la base de données
include('../php/tools/functions.php');
$dbh = dbConnexion();
session_start();

// Récupérer l'ID du professionnel depuis l'URL
if (isset($_GET['professionnel_id'])) {
    $professionnel_id = intval($_GET['professionnel_id']);
    echo "Professionnel ID : ";
    var_dump($professionnel_id);
    echo "<br><br>";
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

// Pour chaque jour entre aujourd'hui et dans 1 semaine
while ($date_en_cours <= $date_dans_1_semaine) {
    // Récupérer le jour de la semaine pour la date en cours
    $jour_semaine = date('N', strtotime($date_en_cours));
    $jour_semaine_fr = $jours_semaine[$jour_semaine - 1];

    // Vérifier si des horaires sont définis pour ce jour de la semaine
    foreach ($horaires as $horaire) {
        if ($horaire['jour_semaine'] == $jour_semaine_fr) {
            // Calculer les créneaux horaires pour ce jour en tenant compte de la durée des rendez-vous
            $heure_debut = strtotime($horaire['heure_debut_matin']);
            $heure_fin = strtotime($horaire['heure_fin_matin']);
            $duree_rdv = intval($horaire['duree_rdv']) * 60;

            // Ajouter les créneaux horaires pour ce jour
            while ($heure_debut < $heure_fin) {
                // Vérifier si un rendez-vous existe pour ce créneau horaire
                $date_heure_debut = date('Y-m-d H:i:s', $heure_debut);
                $date_heure_fin = date('Y-m-d H:i:s', $heure_debut + $duree_rdv);
                $sql = "SELECT COUNT(*) AS count FROM rendez_vous WHERE professionnel_id = :professionnel_id AND date_heure >= :date_heure_debut AND date_heure < :date_heure_fin";
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':professionnel_id', $professionnel_id, PDO::PARAM_INT);
                $stmt->bindParam(':date_heure_debut', $date_heure_debut);
                $stmt->bindParam(':date_heure_fin', $date_heure_fin);
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                // Si aucun rendez-vous trouvé, ajouter ce créneau horaire aux événements disponibles
                if ($result['count'] == 0) {
                    $evenements[] = array(
                        'title' => 'Disponible',
                        'start' => date('Y-m-d\TH:i:s', $heure_debut),
                        'end' => date('Y-m-d\TH:i:s', $heure_debut + $duree_rdv),
                        'url' => 'confirmation_rdv.php?professionnel_id=' . $professionnel_id . '&date_heure=' . date('Y-m-d\TH:i:s', $heure_debut),
                        'backgroundColor' => '#28a745',
                        'borderColor' => '#28a745'
                    );
                }

                // Avancer l'heure de début au prochain créneau horaire
                $heure_debut += $duree_rdv;
            }
        }
    }

    // Passer au jour suivant
    $date_en_cours = date('Y-m-d', strtotime($date_en_cours . ' +1 day'));
}

// Convertir le tableau des événements en format JSON pour l'utiliser dans le JavaScript
$evenements_json = json_encode($evenements);

echo "Événements JSON : ";
var_dump($evenements_json);
echo "<br><br>";

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    center: 'title',
                    right: "prev,next"
                },
                navLinks: true,
                buttonText: {
                    dayGridMonth: 'Mois',
                    timeGridWeek: 'Semaine',
                    timeGridDay: 'Jour',
                    listWeek: 'Liste semaine'
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
    </script>
</head>
<body>
<?php include('../includes/headerNav.php') ?>
<div class="container">
    <h1>Agenda de prise de rendez-vous</h1>
    <p>Sélectionnez un créneau horaire disponible pour prendre rendez-vous :</p>
    <div id="calendar"></div>
</div>
<?php include('../includes/footer.php') ?>
</body>
</html>