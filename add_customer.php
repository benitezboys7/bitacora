<?php
// archivo.php

session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}
// Código backend
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email_proveedor = $_POST['email_proveedor'];

    // Prepara la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO customers (name, email_proveedor) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email_proveedor);

    if ($stmt->execute()) {
        // Redirige a la vista de clientes en el dashboard después de agregar el cliente
        $message = "Operation completed successfully!";
        echo "<script type='text/javascript'>alert('$message');</script>";

        header('Location: dashboard.php?view=customers');
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
<form method="post" action="">
    Name: <input type="text" name="name" required><br>
    Email: <input type="email" name="email_proveedor" required><br>
    <input type="submit" value="Add Customer">
</form>