<?php

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}

session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Devolver una respuesta JSON
header('Content-Type: application/json');
echo json_encode(array(
    'success' => true,
    'message' => 'Logged out successfully'
));
exit();
?>
