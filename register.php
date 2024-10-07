<?php
include 'db_connect.php';
include 'functions.php';

// Проверяем, если сессия не активна, запускаем её
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

global $conn;

// Register user
if (isset($_POST['register'])) {
    $email = $_POST['email'];

    $sql = "SELECT kasutaja_id FROM Kasutajad WHERE email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if (!$result->num_rows > 0) {
        if (IsStrongPassword($_POST['password'])){
            $username = $_POST['username'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Вставляем нового пользователя в базу данных
            $sql = "INSERT INTO Kasutajad (kasutajanimi, email, salasona, loodud) VALUES (?, ?, ?, NOW())";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sss", $username, $email, $password);
            $stmt->execute();

            // Получаем ID только что зарегистрированного пользователя
            $user_id = $conn->insert_id;

            // Сохраняем ID пользователя в сессии для автоматической авторизации
            $_SESSION['user_id'] = $user_id;

            // Перенаправляем на страницу с событиями
            header("Location: events.php");
            exit();

    exit();
        }
        else{
            header("Location: register.php?error=weak_password");
            exit();
        }
    }
    else{
          header("Location: register.php?error=taken_email");
          exit();
    }

}

$conn->close();
?>

<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <title>Registreerimine</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="/css/style.css" rel="stylesheet">
    <script src="js/auth_script.js"></script>

</head>

<body onload="DisableRegBtn();  displayErrorMessage();">
    <div class="container mt-5">
        <h2 class="text-center mb-4">Registreerimine</h2>
        <div class="card p-4">
            <form method="post" action="register.php">
                <div id="error-div" style="color: red;"></div>

                <div class="mb-3">
                    <label for="username" class="form-label">Kasutajanimi</label>
                    <input oninput="RegisterFieldsValidation()" id="reg-name" type="text" name="username" class="form-control" placeholder="Kasutajanimi" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input oninput="RegisterFieldsValidation()" id="reg-email" type="email" name="email" class="form-control" placeholder="Email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Salasõna</label>
                    <input oninput="RegisterFieldsValidation()" id="reg-pass" type="password" name="password" class="form-control" placeholder="Salasõna" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Kinnita salasõna</label>
                    <input oninput="RegisterFieldsValidation()" id="reg-pass-confirm" type="password"  class="form-control" placeholder="Salasõna" required>
                </div>
                <button type="submit" name="register" id="reg-btn" class="btn btn-custom w-100">Registreeri</button>
            </form>
            <div class="text-center mt-3">
                <a href="login.php">Logi sisse</a>
                <a href="events.php">Tagasi</a>

            </div>
        </div>
    </div>
    <script>
        function displayErrorMessage() {
            const urlParams = new URLSearchParams(window.location.search);
            const error = urlParams.get('error');
            const errorDiv = document.getElementById('error-div');

            if (error === 'weak_password') {
                errorDiv.innerText = "Parool on liiga nõrk. Proovige midagi keerukamat.";
            } else if (error === 'taken_email') {
                errorDiv.innerText = "Email on juba hõivatud. Valige mõni muu.";
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