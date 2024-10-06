<?php
include 'db_connect.php';
global $conn;
// Register user
if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $sql = "INSERT INTO Kasutajad (kasutajanimi, email, salasona, loodud) VALUES (?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $email, $password);
    $stmt->execute();
    echo "Registration successful";
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Registreerimine</title>
</head>
<body>
<h2>Registreerimine</h2>
<form method="post" action="register.php">
    <input type="text" name="username" placeholder="Kasutajanimi" required><br>
    <input type="email" name="email" placeholder="Email" required><br>
    <input type="password" name="password" placeholder="Salasõna" required><br>
    <button type="submit" name="register">Registreeru</button>
</form>
<a href="login.php">Logi sisse</a>
</body>
</html>