<?php
session_start();

include 'db.php';

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
    if (password_verify($password, $row['password'])) { // Usar password_verify para verificar la contraseña encriptada
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['user_email'] = $row['email'];
        $response = ["success" => true, "message" => "Login exitoso!", "redirect" => "dashboard.php"];
    } else {
        $response = ["success" => false, "message" => "Contraseña incorrecta"];
    }
}

echo json_encode($response);

$stmt->close();
$conn->close();
?>
