<?php

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}
include 'db.php';

header('Content-Type: application/json'); // Establece el tipo de contenido como JSON

$sql = "SELECT * FROM customers";
$result = $conn->query($sql);

$customers = [];
if ($result->num_rows > 0) {
    // Recupera todos los datos y los almacena en un array
    while($row = $result->fetch_assoc()) {
        $customers[] = $row;
    }
}

// Convierte el array a JSON y lo devuelve
echo json_encode($customers);

$conn->close();
?>
