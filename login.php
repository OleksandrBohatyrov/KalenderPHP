<?php
// File: login.php
include 'db_connect.php';
global $conn;
// Login user
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT kasutaja_id, salasõna FROM Kasutajad WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['salasõna'])) {
            $_SESSION['user_id'] = $user['kasutaja_id'];
            header("Location: events.php");
            exit();
        } else {
            echo "Invalid password";
        }
    } else {
        echo "No user found with this email";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Logi sisse</title>
</head>
<body>
<h2>Logi sisse</h2>
<form method="post" action="login.php">
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Salasõna" required><br>
    <button type="submit" name="login">Logi sisse</button>
</form>
<a href="register.php">Registreeru</a>
</body>
</html>
