<?php
include 'database.php';

$id = $_GET['id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $sql = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: customers.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    $sql = "SELECT * FROM customers WHERE id=$id";
    $result = $conn->query($sql);
    $customer = $result->fetch_assoc();
}
?>

<h1>Edit Customer</h1>
<form method="post" action="">
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?php echo $customer['name']; ?>" required>
    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $customer['email']; ?>" required>
    <label for="phone">Phone:</label>
    <input type="text" name="phone" value="<?php echo $customer['phone']; ?>">
    <button type="submit">Update Customer</button>
</form>
