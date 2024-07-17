<?php
$servername = "localhost";
$username = "u686972174_admis151522";
$password = "Admis.database151522!";
$dbname = "u686972174_bitacoradb";

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}
?>
