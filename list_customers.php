<?php
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
