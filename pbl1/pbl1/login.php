<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="CSS/login.css">
    <script>
        function validateForm() {
            const username = document.getElementById("username").value;
            const password = document.getElementById("password").value;

            if (!username || !password) {
                alert("Semua field harus diisi!");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>SIPRESMA</h1>
        <h3>Silahkan Melakukan Login</h3>
        <form action="proses_login.php" method="POST" onsubmit="return validateForm()">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <button type="submit" class="login-button">Login</button>
        </form>
    </div>
</body>
</html>
