<?php

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}
include 'db.php';

$sql = "SELECT id, name, email_proveedor FROM customers";
$result = $conn->query($sql);

$customers = array();
while($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

$response = array('customers' => $customers);
echo json_encode($response);

$conn->close();
?>
