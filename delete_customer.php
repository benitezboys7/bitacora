<?php
include 'db.php';

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
