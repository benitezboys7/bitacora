<?php
include 'db.php';

$response = array('success' => false, 'message' => '');
$redirect_url = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE customers SET name='$name', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        $response['success'] = true;
        $response['message'] = "Record updated successfully";
        $redirect_url = 'dashboard.php?view=customers'; // Establecer URL de redirección
    } else {
        $response['message'] = "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
} else {
    $response['message'] = "Invalid request method";
}

echo json_encode($response);

// Redirigir después de completar el procesamiento
if ($redirect_url) {
    header('Location: ' . $redirect_url);
    exit();
}
?>
