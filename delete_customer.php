<?php
include 'db.php';

$id = $_GET['id'];

$sql = "DELETE FROM customers WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo "Record deleted successfully";
    header("Location: dashboard.php?view=customers");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
header("Location: dashboard.php?view=customers"); // Redirigir después de eliminar
exit();
?>
