<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

// Configuración de la base de datos
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "database_name";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

$sql = "SELECT id, name, email FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $customers = [];
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
    echo json_encode(["customers" => $customers]);
} else {
    echo json_encode(["customers" => []]);
}

$conn->close();
?>
