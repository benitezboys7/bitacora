<?php
session_start();

$servername = "localhost";
$username = "u686972174_admis151522";
$password = "Admis.database151522!";
$dbname = "u686972174_bitacoradb";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

$email = $_POST['email'];
$password = $_POST['password'];

$sql = "SELECT * FROM users WHERE email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

$response = ["success" => false, "message" => "Usuario no encontrado"];

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if ($password === $row['password']) { // Asegúrate de que el método de verificación de contraseña coincida con el almacenamiento
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        $response = ["success" => true, "message" => "Login exitoso!"];
    } else {
        $response = ["success" => false, "message" => "Contraseña incorrecta"];
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
