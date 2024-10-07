<?php
include 'db_connect.php';
include 'includes/nav.php'; // Подключаем навигацию
global $conn;
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle delete reminder request
if (isset($_GET['delete'])) {
    $reminder_id = $_GET['delete'];
    $delete_sql = "DELETE FROM `meeldetuletused` WHERE meeldetuletus_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $reminder_id);
    $delete_stmt->execute();
    echo "Reminder deleted successfully";
}

// Handle update reminder request
if (isset($_POST['update_reminder'])) {
    $reminder_id = $_POST['reminder_id'];
    $reminder_time = $_POST['reminder_time'];
    $update_sql = "UPDATE `meeldetuletused` SET meeldetuletuse_aeg = ? WHERE meeldetuletus_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("si", $reminder_time, $reminder_id);
    $update_stmt->execute();
    echo "Reminder updated successfully";
}

// Handle add reminder request
if (isset($_POST['add_reminder'])) {
    $event_id = $_POST['event_id'];
    $reminder_time = $_POST['reminder_time'];

    $insert_sql = "INSERT INTO `meeldetuletused` (sondmus_id, meeldetuletuse_aeg) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("is", $event_id, $reminder_time);
    $insert_stmt->execute();
    echo "New reminder added successfully";
}

// Fetch reminders for logged-in user
$reminders = [];
$reminder_sql = "SELECT `meeldetuletused`.meeldetuletus_id, `meeldetuletused`.meeldetuletuse_aeg, `sondmused`.pealkiri 
                 FROM `meeldetuletused` 
                 INNER JOIN `sondmused` ON `meeldetuletused`.sondmus_id = `sondmused`.sondmus_id 
                 WHERE `sondmused`.kasutaja_id = ?";
$reminder_stmt = $conn->prepare($reminder_sql);
$reminder_stmt->bind_param("i", $user_id);
$reminder_stmt->execute();
$reminder_result = $reminder_stmt->get_result();

while ($reminder = $reminder_result->fetch_assoc()) {
    $reminders[] = $reminder;
}

// Fetch events for logged-in user to populate dropdown
$events = [];
$event_sql = "SELECT sondmus_id, pealkiri FROM `sondmused` WHERE kasutaja_id = ?";
$event_stmt = $conn->prepare($event_sql);
$event_stmt->bind_param("i", $user_id);
$event_stmt->execute();
$event_result = $event_stmt->get_result();

while ($event = $event_result->fetch_assoc()) {
    $events[] = $event;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <title>Meeldetuletuste haldamine</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <link href="/css/style.css" rel="stylesheet">
    <script src="js/reminders_script.js"></script>
</head>

<body onload="disableBtn();">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Meeldetuletuste haldamine</h2>

        <!-- Форма добавления нового напоминания -->
        <div class="card mb-4">
            <div class="card-header text-white">Lisa uus meeldetuletus</div>
            <div class="card-body">
                <form method="post" action="manage_reminders.php">
                    <div class="mb-3">
                        <label for="event_id" class="form-label">Vali sündmus:</label>
                        <select  name="event_id" id="event_id" class="form-select" required>
                            <?php foreach ($events as $event): ?>
                                <option value="<?php echo $event['sondmus_id']; ?>">
                                    <?php echo htmlspecialchars($event['pealkiri']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="reminder_time" class="form-label">Meeldetuletuse aeg:</label>
                        <input oninput="fieldsValidation()" type="datetime-local" name="reminder_time" id="reminder_time" class="form-control"
                            required>
                    </div>

                    <button type="submit" name="add_reminder" class="btn btn-custom w-100" id="rem-btn">Lisa meeldetuletus</button>
                </form>
            </div>
        </div>

        <!-- Таблица с напоминаниями -->
        <?php if (count($reminders) > 0): ?>
            <h3 class="mb-3">Sinu meeldetuletused</h3>
            <table class="table table-bordered table-striped">
                <thead class="table-dark">
                    <tr>
                        <th>Sündmuse pealkiri</th>
                        <th>Meeldetuletuse aeg</th>
                        <th>Tegevused</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($reminders as $reminder): ?>
                        <tr>
                            <form method="post" action="manage_reminders.php">
                                <td><?php echo htmlspecialchars($reminder['pealkiri']); ?></td>
                                <td>
                                    <input type="hidden" name="reminder_id"
                                        value="<?php echo $reminder['meeldetuletus_id']; ?>">
                                    <input type="datetime-local" name="reminder_time"
                                        value="<?php echo date('Y-m-d\TH:i', strtotime($reminder['meeldetuletuse_aeg'])); ?>"
                                        class="form-control" required>
                                </td>
                                <td>
                                    <button type="submit" name="update_reminder" class="btn btn-warning mb-2">Muuda</button>
                                    <a href="manage_reminders.php?delete=<?php echo $reminder['meeldetuletus_id']; ?>"
                                        class="btn btn-danger"
                                        onclick="return confirm('Kas olete kindel, et soovite kustutada meeldetuletuse?');">Kustuta</a>
                                </td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-info">Teil pole meeldetuletusi.</p>
        <?php endif; ?>

        <!-- Кнопка для возврата на страницу событий -->
        <div class="text-center mt-4">
            <a href="events.php" class="btn btn-secondary">Tagasi sündмuste juurde</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
<?php include 'includes/footer.html'; ?>

</body>

</html>