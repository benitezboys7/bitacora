<?php
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
