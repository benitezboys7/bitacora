<?php
include 'db.php';

$id = $_GET['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];

    $sql = "UPDATE customers SET name='$name', email='$email' WHERE id=$id";

    if ($conn->query($sql) === TRUE) {
        echo "Record updated successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
    header("Location: dashboard.php"); // Redirigir después de actualizar
    exit();
}

$sql = "SELECT * FROM customers WHERE id=$id";
$result = $conn->query($sql);
$customer = $result->fetch_assoc();

?>

<form method="post" action="">
    Name: <input type="text" name="name" value="<?php echo $customer['name']; ?>" required><br>
    Email: <input type="email" name="email" value="<?php echo $customer['email']; ?>" required><br>
    <input type="submit" value="Update Customer">
</form>
