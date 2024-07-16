<?php
session_start();
header('Content-Type: application/json');

$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "u686972174_admis151522"; // Cambia esto si tu usuario es diferente
$password = "Admis.database151522!"; // Cambia esto si tu contraseña es diferente
$dbname = "u686972174_bitacoradb"; // Cambia esto al nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

// Prepara y ejecuta la consulta
$sql = "SELECT * FROM users WHERE email = ? AND password = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $email, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $_SESSION['user'] = $email;
    echo json_encode(['success' => true, 'message' => 'Login successful']);
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
}

$stmt->close();
$conn->close();
?>
