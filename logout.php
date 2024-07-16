<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

// Responde con un estado de éxito
http_response_code(200);
?>
