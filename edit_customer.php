<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);

    $sql = "UPDATE customers SET name='$name', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        header("Location: dashboard.php?view=customers"); // Redirigir después de actualizar
        exit();
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    exit();
}

$sql = "SELECT * FROM customers WHERE id=$id";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $customer = $result->fetch_assoc();
} else {
    echo "No customer found with ID $id";
    exit();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Edit Customer</title>
</head>
<body>
    <h2>Edit Customer</h2>
    <form method="post" action="">
        Name: <input type="text" name="name" value="<?php echo htmlspecialchars($customer['name']); ?>" required><br>
        Email: <input type="email" name="email" value="<?php echo htmlspecialchars($customer['email']); ?>" required><br>
        <input type="submit" value="Update Customer">
    </form>
</body>
</html>
