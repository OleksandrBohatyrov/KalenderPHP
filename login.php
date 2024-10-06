<?php
include 'db_connect.php';
include 'navbar.php';
global $conn;
// Login user
if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT kasutaja_id, salasona FROM Kasutajad WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['salasona'])) {
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
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">
</head>
<body >
    <div class="container mt-5">
        <h2 class="text-center mb-4">Logi sisse</h2>
        
        <!-- Форма входа -->
        <div class="card p-4">
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Salasõna</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Salasõna" required>
                </div>
                <button type="submit" name="login" class="btn btn-custom w-100">Logi sisse</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php">Registreeru</a>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
</body>
</html>
