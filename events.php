<?php
include 'db_connect.php';
global $conn;

// Fetch events for logged-in user
$events = [];
$reminders = [];
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Fetch events
    $sql = "SELECT sondmus_id, pealkiri, kirjeldus, algus_aeg, lopp_aeg FROM `sondmused` WHERE kasutaja_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $events[] = [
            'id' => $row['sõndmus_id'],
            'title' => $row['pealkiri'],
            'description' => $row['kirjeldus'],
            'start' => $row['algus_aeg'],
            'end' => $row['lopp_aeg']
        ];
    }

    // Fetch reminders
    $reminder_sql = "SELECT meeldetuletuse_aeg FROM `meeldetuletused` INNER JOIN `sondmused` ON `Meeldetuletused`.sondmus_id = `sondmused`.sondmus_id WHERE `sondmused`.kasutaja_id = ?";
    $reminder_stmt = $conn->prepare($reminder_sql);
    $reminder_stmt->bind_param("i", $user_id);
    $reminder_stmt->execute();
    $reminder_result = $reminder_stmt->get_result();

    while ($reminder = $reminder_result->fetch_assoc()) {
        $reminders[] = $reminder['meeldetuletuse_aeg'];
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js'></script>
    <script src='https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js'></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
</head>
<body>
<h2>Sinu sündmused:</h2>

<!-- Button to redirect to manage_events.php -->
<a href="manage_events.php"><button>Halda sündmusi</button></a>
<!-- Button to redirect to manage_reminders.php -->
<a href="manage_reminders.php"><button>Halda meeldetuletusi</button></a>

<!-- Calendar container -->
<div id='calendar' style='margin-top: 20px;'></div>

<!-- Modal for displaying event details -->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="eventModalLabel">Sündmuse detailid</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="eventTitle"></p>
                <p id="eventDescription"></p>
                <p id="eventStart"></p>
                <p id="eventEnd"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Sulge</button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay'
            },
            events: <?php echo json_encode($events); ?>,
            eventClick: function (info) {
                $('#eventTitle').text('Pealkiri: ' + info.event.title);
                $('#eventDescription').text('Kirjeldus: ' + info.event.extendedProps.description);
                $('#eventStart').text('Algusaeg: ' + new Date(info.event.start).toLocaleString());
                $('#eventEnd').text('Lõpuaeg: ' + (info.event.end ? new Date(info.event.end).toLocaleString() : 'Pole määratud'));
                $('#eventModal').modal('show');
            }
        });

        calendar.render();

        // Reminders from PHP
        var reminders = <?php echo json_encode($reminders); ?>;

        // Function to check reminders every minute
        setInterval(function () {
            var now = new Date();
            reminders.forEach(function (reminder) {
                var reminderTime = new Date(reminder);
                if (now.getFullYear() === reminderTime.getFullYear() &&
                    now.getMonth() === reminderTime.getMonth() &&
                    now.getDate() === reminderTime.getDate() &&
                    now.getHours() === reminderTime.getHours() &&
                    now.getMinutes() === reminderTime.getMinutes()) {
                    alert("Meeldetuletus: on aeg ühe teie sündmuse jaoks!");
                }
            });
        }, 30000);
    });
</script>
</body>
</html>
