<?php

include 'db_connect.php';
global $conn;
// Fetch events for logged-in user
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql = "SELECT * FROM Sõndmused WHERE kasutaja_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    echo "<h2>Your Events:</h2>";
    while ($row = $result->fetch_assoc()) {
        echo "<div><h3>" . $row['pealkiri'] . "</h3><p>" . $row['kirjeldus'] . "</p><p>Start: " . $row['algus_aeg'] . "</p><p>End: " . $row['lõpp_aeg'] . "</p></div>";
    }
}
$conn->close();
?>