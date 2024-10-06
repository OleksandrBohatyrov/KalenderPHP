<?php
include 'db_connect.php';
global $conn;
// Fetch events for logged-in user and prepare them for the calendar
$events = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM `sõndmused` WHERE kasutaja_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'title' => $row['pealkiri'],
            'start' => $row['algus_aeg'],
            'end' => $row['lõpp_aeg']
        ];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sündmused</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');

            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: <?php echo json_encode($events); ?>, // Events fetched from the PHP array
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                }
            });

            calendar.render();
        });
    </script>
</head>
<body>
<h2>Sinu sündmused:</h2>

<!-- Button to redirect to add_event.php -->
<a href="add_event.php"><button>Lisa uus sündmus</button></a>

<!-- Calendar container -->
<div id='calendar' style='margin-top: 20px;'></div>
</body>
</html>
