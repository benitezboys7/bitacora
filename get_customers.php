<?php
include 'db.php';

$sql = "SELECT id, name, email FROM customers";
$result = $conn->query($sql);

$customers = array();
while($row = $result->fetch_assoc()) {
    $customers[] = $row;
}

$response = array('customers' => $customers);
echo json_encode($response);

$conn->close();
?>
