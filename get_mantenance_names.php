<?php
// Aquí deberías agregar la lógica para obtener los nombres de mantenimiento
// Por ejemplo:
$response = array('maintenance' => array());

$sql = "SELECT id, name FROM maintenance"; // Asegúrate de tener esta tabla y datos
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['maintenance'][] = $row;
    }
}

$conn->close();
echo json_encode($response);
?>
