<?php
session_start();

// Conexión a la base de datos
$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "u686972174_admis151522"; // Cambia esto si tu usuario es diferente
$password = "Admis.database151522!"; // Cambia esto si tu contraseña es diferente
$dbname = "u686972174_bitacoradb"; // Cambia esto al nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Obtener los datos del formulario
$email = $_POST['email'];
$password = $_POST['password'];

// Consulta para verificar el usuario
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verificar la contraseña
    if ($password === $row['password']) { // Comparar directamente ya que las contraseñas no están cifradas
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        echo "Login exitoso!";
        // Redireccionar al dashboard
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Contraseña incorrecta<br>";
        echo "Stored Password from DB: " . $row['password'] . "<br>";
    }
} else {
    echo "Usuario no encontrado";
}

$stmt->close();
$conn->close();
?>
