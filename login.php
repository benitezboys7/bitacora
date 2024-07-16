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
    die(json_encode(["success" => false, "message" => "Conexión fallida: " . $conn->connect_error]));
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

$response = ["success" => false, "message" => "Usuario no encontrado"];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    // Verificar la contraseña
    if ($password === $row['password']) { // Comparar directamente ya que las contraseñas no están cifradas
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        $response = ["success" => true, "message" => "Login exitoso!"];
    } else {
        $response = ["success" => false, "message" => "Contraseña incorrecta"];
    }
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
