<?php
include 'db_connect.php';
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

    $insert_sql = "INSERT INTO `meeldetuletused` (sõndmus_id, meeldetuletuse_aeg) VALUES (?, ?)";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("is", $event_id, $reminder_time);
    $insert_stmt->execute();
    echo "New reminder added successfully";
}

// Fetch reminders for logged-in user
$reminders = [];
$reminder_sql = "SELECT `meeldetuletused`.meeldetuletus_id, `meeldetuletused`.meeldetuletuse_aeg, `sõndmused`.pealkiri 
                 FROM `meeldetuletused` 
                 INNER JOIN `sõndmused` ON `meeldetuletused`.sõndmus_id = `sõndmused`.sõndmus_id 
                 WHERE `sõndmused`.kasutaja_id = ?";
$reminder_stmt = $conn->prepare($reminder_sql);
$reminder_stmt->bind_param("i", $user_id);
$reminder_stmt->execute();
$reminder_result = $reminder_stmt->get_result();

while ($reminder = $reminder_result->fetch_assoc()) {
    $reminders[] = $reminder;
}

// Fetch events for logged-in user to populate dropdown
$events = [];
$event_sql = "SELECT sõndmus_id, pealkiri FROM `sõndmused` WHERE kasutaja_id = ?";
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
</head>
<body>
<h2>Meeldetuletuste haldamine</h2>

<!-- Form to add new reminder -->
<h3>Lisa uus meeldetuletus</h3>
<form method="post" action="manage_reminders.php">
    <label for="event_id">Vali sündmus:</label>
    <select name="event_id" id="event_id" required>
        <?php foreach ($events as $event): ?>
            <option value="<?php echo $event['sõndmus_id']; ?>">
                <?php echo htmlspecialchars($event['pealkiri']); ?>
            </option>
        <?php endforeach; ?>
    </select><br><br>

    <label for="reminder_time">Meeldetuletuse aeg:</label>
    <input type="datetime-local" name="reminder_time" id="reminder_time" required><br><br>

    <button type="submit" name="add_reminder">Lisa meeldetuletus</button>
</form>

<hr>

<!-- Display reminders with options to edit or delete -->
<?php if (count($reminders) > 0): ?>
    <h3>Sinu meeldetuletused</h3>
    <table border="1">
        <tr>
            <th>Sündmuse pealkiri</th>
            <th>Meeldetuletuse aeg</th>
            <th>Tegevused</th>
        </tr>
        <?php foreach ($reminders as $reminder): ?>
            <tr>
                <form method="post" action="manage_reminders.php">
                    <td><?php echo htmlspecialchars($reminder['pealkiri']); ?></td>
                    <td>
                        <input type="hidden" name="reminder_id" value="<?php echo $reminder['meeldetuletus_id']; ?>">
                        <input type="datetime-local" name="reminder_time" value="<?php echo date('Y-m-d\TH:i', strtotime($reminder['meeldetuletuse_aeg'])); ?>" required>
                    </td>
                    <td>
                        <button type="submit" name="update_reminder">Muuda</button>
                        <a href="manage_reminders.php?delete=<?php echo $reminder['meeldetuletus_id']; ?>" onclick="return confirm('Kas olete kindel, et soovite kustutada meeldetuletuse?');">Kustuta</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Teil pole meeldetuletusi.</p>
<?php endif; ?>

<!-- Button to redirect back to events.php -->
<a href="events.php"><button>Tagasi sündmuste juurde</button></a>
</body>
</html>
