<?php
include 'db.php';

$response = array('providers' => array());

$sql = "SELECT id, name FROM customers";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $response['providers'][] = $row;
    }
}

$conn->close();
echo json_encode($response);
?>
