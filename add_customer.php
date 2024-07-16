<?php
include 'database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
    if ($conn->query($sql) === TRUE) {
        header("Location: customers.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
}
?>

<h1>Add New Customer</h1>
<form method="post" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" required>
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="phone">Phone:</label>
    <input type="text" name="phone">
    <button type="submit">Add Customer</button>
</form>
