<?php
include 'db_connect.php';
include 'navbar.php';
global $conn;

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT kasutaja_id, salasona FROM kasutajad WHERE email = ?";
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
            header("Location: login.php?error=invalid_password");
            exit();
        }
    } else {
        header("Location: login.php?error=user_not_found");
        exit();
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
    <script src="js/auth_script.js"></script>

</head>
<body onload="disableLoginBtn(); displayErrorMessage();">
   <div class="container mt-5">
        <h2 class="text-center mb-4">Logi sisse</h2>

        <!-- Форма входа -->
        <div class="card p-4">
            <div id="error-div" style="color: red;"></div>
            <form method="post" action="login.php">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input oninput="loginFieldsValidation()" type="email" class="form-control" id="login-email" name="email" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Salasõна</label>
                    <input oninput="loginFieldsValidation()" type="password" class="form-control" id="login-pass" name="password" placeholder="Salasõна" required>
                </div>
                <button type="submit" name="login" id="login-btn" class="btn btn-custom w-100">Logi sisse</button>
            </form>
            <div class="text-center mt-3">
                <a href="register.php">Registreeru</a>
                <a href="events.php">Tagasi</a>
            </div>
        </div>
    </div>

    <script>
        function displayErrorMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const errorDiv = document.getElementById('error-div');

            if (error === 'invalid_password') {
                errorDiv.innerText = "Vale salasõna. Palun proovige uuesti.";
            } else if (error === 'user_not_found') {
                errorDiv.innerText = "Kasutajat ei leitud selle e-mailiga.";
            }
        }
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/js/bootstrap.min.js"></script>
<?php include 'includes/footer.html'; ?>

</body>
</html>
