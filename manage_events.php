<?php
include 'db_connect.php';
global $conn;
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Handle delete event request
if (isset($_GET['delete'])) {
    $event_id = $_GET['delete'];
    $delete_sql = "DELETE FROM `sõndmused` WHERE sündmus_id = ?";
    $delete_stmt = $conn->prepare($delete_sql);
    $delete_stmt->bind_param("i", $event_id);
    $delete_stmt->execute();
    echo "Event deleted successfully";
}

// Handle update event request
if (isset($_POST['update_event'])) {
    $event_id = $_POST['event_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $update_sql = "UPDATE `sõndmused` SET pealkiri = ?, kirjeldus = ?, algus_aeg = ?, lõpp_aeg = ? WHERE sõndmus_id = ?";
    $update_stmt = $conn->prepare($update_sql);
    $update_stmt->bind_param("ssssi", $title, $description, $start_time, $end_time, $event_id);
    $update_stmt->execute();
    echo "Event updated successfully";
}

// Handle add event request
if (isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    $insert_sql = "INSERT INTO `sõndmused` (kasutaja_id, pealkiri, kirjeldus, algus_aeg, lõpp_aeg, loodud) VALUES (?, ?, ?, ?, ?, NOW())";
    $insert_stmt = $conn->prepare($insert_sql);
    $insert_stmt->bind_param("issss", $user_id, $title, $description, $start_time, $end_time);
    $insert_stmt->execute();
    echo "New event added successfully";
}

// Fetch events for logged-in user
$events = [];
$event_sql = "SELECT sõndmus_id, pealkiri, kirjeldus, algus_aeg, lõpp_aeg FROM `sõndmused` WHERE kasutaja_id = ?";
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
    <title>Sündmuste haldamine</title>
</head>
<body>
<h2>Sündmuste haldamine</h2>

<!-- Form to add new event -->
<h3>Lisa uus sündmus</h3>
<form method="post" action="manage_events.php">
    <label for="title">Pealkiri:</label>
    <input type="text" name="title" id="title" required><br><br>

    <label for="description">Kirjeldus:</label>
    <textarea name="description" id="description" required></textarea><br><br>

    <label for="start_time">Algusaeg:</label>
    <input type="datetime-local" name="start_time" id="start_time" required><br><br>

    <label for="end_time">Lõpuaeg:</label>
    <input type="datetime-local" name="end_time" id="end_time" required><br><br>

    <button type="submit" name="add_event">Lisa sündmus</button>
</form>

<hr>

<!-- Display events with options to edit or delete -->
<?php if (count($events) > 0): ?>
    <h3>Sinu sündmused</h3>
    <table border="1">
        <tr>
            <th>Pealkiri</th>
            <th>Kirjeldus</th>
            <th>Algusaeg</th>
            <th>Lõpuaeg</th>
            <th>Tegevused</th>
        </tr>
        <?php foreach ($events as $event): ?>
            <tr>
                <form method="post" action="manage_events.php">
                    <td>
                        <input type="hidden" name="event_id" value="<?php echo $event['sõndmus_id']; ?>">
                        <input type="text" name="title" value="<?php echo htmlspecialchars($event['pealkiri']); ?>" required>
                    </td>
                    <td>
                        <textarea name="description" required><?php echo htmlspecialchars($event['kirjeldus']); ?></textarea>
                    </td>
                    <td>
                        <input type="datetime-local" name="start_time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['algus_aeg'])); ?>" required>
                    </td>
                    <td>
                        <input type="datetime-local" name="end_time" value="<?php echo date('Y-m-d\TH:i', strtotime($event['lõpp_aeg'])); ?>" required>
                    </td>
                    <td>
                        <button type="submit" name="update_event">Muuda</button>
                        <a href="manage_events.php?delete=<?php echo $event['sõndmus_id']; ?>" onclick="return confirm('Kas olete kindel, et soovite kustutada sündmuse?');">Kustuta</a>
                    </td>
                </form>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>Teil pole sündmusi.</p>
<?php endif; ?>

<!-- Button to redirect back to events.php -->
<a href="events.php"><button>Tagasi sündmuste juurde</button></a>
</body>
</html>
