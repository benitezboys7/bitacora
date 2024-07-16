<?php
header('Content-Type: application/json');

$servername = "localhost"; // Cambia esto si tu servidor es diferente
$username = "u686972174_admis151522"; // Cambia esto si tu usuario es diferente
$password = "Admis.database151522!"; // Cambia esto si tu contraseña es diferente
$dbname = "u686972174_bitacoradb"; // Cambia esto al nombre de tu base de datos

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

// Verifica si el usuario ya existe
$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'User already exists']);
} else {
    // Inserta el nuevo usuario
    $sql = "INSERT INTO users (name, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $name, $email, $password);

    if ($stmt->execute()) {
        echo json_encode(['success' => true, 'message' => 'Registration successful']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Registration failed']);
    }

    $stmt->close();
}

$conn->close();
?>
