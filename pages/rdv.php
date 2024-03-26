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
if(isset($_GET['professionnel_id'])) {
    $professionnel_id = intval($_GET['professionnel_id']);
} else {
    // Rediriger vers une page d'erreur si l'ID n'est pas fourni
    header("Location: ../pages/consultations.php");
    exit();
}

// Récupérer les horaires disponibles pour ce professionnel
$sql = "SELECT * FROM horaires_professionnels WHERE professionnel_id = :professionnel_id";
$stmt = $dbh->prepare($sql);
$stmt->bindParam(':professionnel_id', $professionnel_id, PDO::PARAM_INT);
$stmt->execute();
$horaires = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prise de rendez-vous</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="fullcalendar/core/main.css">
    <link rel="stylesheet" href="fullcalendar/daygrid/main.css">
    <link rel="stylesheet" href="fullcalendar/list/main.css">
    <link rel="stylesheet" href="fullcalendar/interaction/main.css">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script src="
https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.11/index.global.min.js
"></script>
    <script>

        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                initialDate: '2024-03-07',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                events: [
                    {
                        title: 'All Day Event',
                        start: '2024-03-01'
                    },
                    {
                        title: 'Long Event',
                        start: '2024-03-07',
                        end: '2024-03-10'
                    },
                    {
                        groupId: '999',
                        title: 'Repeating Event',
                        start: '2024-03-09T16:00:00'
                    },
                    {
                        groupId: '999',
                        title: 'Repeating Event',
                        start: '2024-03-16T16:00:00'
                    },
                    {
                        title: 'Conference',
                        start: '2024-03-11',
                        end: '2024-03-13'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-03-12T10:30:00',
                        end: '2024-03-12T12:30:00'
                    },
                    {
                        title: 'Lunch',
                        start: '2024-03-12T12:00:00'
                    },
                    {
                        title: 'Meeting',
                        start: '2024-03-12T14:30:00'
                    },
                    {
                        title: 'Birthday Party',
                        start: '2024-03-13T07:00:00'
                    },
                    {
                        title: 'Click for Google',
                        url: 'https://google.com/',
                        start: '2024-03-28'
                    }
                ]
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
<script src="fullcalendar/core/main.js"></script>
<script src="fullcalendar/daygrid/main.js"></script>
<script src="fullcalendar/list/main.js"></script>
<script src="fullcalendar/interaction/main.js"></script>
</body>
</html>
