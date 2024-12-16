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
        // Tampilkan pesan error berdasarkan parameter URL
        window.onload = function() {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.has('error')) {
                const error = urlParams.get('error');
                let message = "";

                switch (error) {
                    case "invalid":
                        message = "Username atau password salah!";
                        break;
                    case "query":
                        message = "Terjadi kesalahan pada sistem. Silakan coba lagi.";
                        break;
                    default:
                        message = "Terjadi kesalahan. Silakan coba lagi.";
                }

                if (message) {
                    alert(message);
                }
            }
        }
    </script>
</head>
<body>
        <!-- Tombol Kembali -->
<a href="beranda.php" class="back">
    <img src="https://cdn-icons-png.flaticon.com/512/545/545682.png" alt="Kembali" class="back-icon">
    Kembali
</a>

    <!-- Form Login -->
    <div class="container">
        <img src="gambar/jti.png" class="logo" alt="Logo JTI">
        <h1>SIPRESMA</h1>
        <h4>Silahkan Melakukan Login</h4>
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
