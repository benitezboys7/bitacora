<?php
session_start();

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["message" => "Unauthorized"]);
    exit();
}

$servername = "localhost";
$username = "u686972174_admis151522";
$password = "Admis.database151522!";
$dbname = "u686972174_bitacoradb";

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
