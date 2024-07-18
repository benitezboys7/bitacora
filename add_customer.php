<?php
include 'db.php';


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    // Prepara la consulta para evitar inyecciones SQL
    $stmt = $conn->prepare("INSERT INTO customers (name, email) VALUES (?, ?)");
    $stmt->bind_param("ss", $name, $email);

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
    Email: <input type="email" name="email" required><br>
    <input type="submit" value="Add Customer">
</form>