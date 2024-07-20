<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include 'db.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Conexión fallida: " . $conn->connect_error);
    }

    $email = $_POST['email'];
    $password = $_POST['password'];

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insertar el usuario en la base de datos
    $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $email, $hashed_password);

    if ($stmt->execute()) {
        $message = "Registro exitoso";
        header("Location: login.php");
        exit();
    } else {
        $message = "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="styles.css"> <!-- Asegúrate de tener un archivo CSS para estilizar tu formulario -->
</head>
<body>
    <div class="container">
        <h2>Registro de Usuario</h2>
        <?php if (!empty($message)): ?>
            <p><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="register.php" method="post" id="registerForm">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required>
            
            <label for="password">Contraseña:</label>
            <input type="password" id="password" name="password" required>
            
            <button type="submit">Registrar</button>
        </form>
    </div>
    <script src="scripts.js"></script>
</body>
</html>
