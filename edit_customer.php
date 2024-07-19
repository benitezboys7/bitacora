<?php

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}

include 'db.php';

$response = array('success' => false, 'message' => '');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE customers SET name='$name', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Record updated successfully";
      

    } else {
        $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);
?>
