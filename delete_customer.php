<?php
include 'db.php';

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}

$id = $_GET['id'];

$sql = "DELETE FROM customers WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    $message = "Record deleted successfully";
    echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: dashboard.php?view=customers"); // Redirigir después de eliminar
exit();
?>
