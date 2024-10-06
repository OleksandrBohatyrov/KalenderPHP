<?php
// File: add_event.php
include 'db_connect.php';
global $conn;
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Add event
if (isset($_POST['add_event'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];
    $user_id = $_SESSION['user_id'];

    $sql = "INSERT INTO sõndmused (kasutaja_id, pealkiri, kirjeldus, algus_aeg, lõpp_aeg, loodud) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("issss", $user_id, $title, $description, $start_time, $end_time);
    $stmt->execute();
    echo "Event added successfully";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Lisa Üksndmus</title>
</head>
<body>
<h2>Lisa Üksndmus</h2>
<form method="post" action="add_event.php">
    <input type="text" name="title" placeholder="Pealkiri" required><br>
    <textarea name="description" placeholder="Kirjeldus" required></textarea><br>
    <input type="datetime-local" name="start_time" required><br>
    <input type="datetime-local" name="end_time" required><br>
    <button type="submit" name="add_event">Lisa Üksndmus</button>
</form>

<!-- Button to redirect back to events.php -->
<a href="events.php"><button>Tagasi sündmuste juurde</button></a>
</body>
</html>
