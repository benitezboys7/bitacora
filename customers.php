<?php
include 'database.php';

// Manejo de solicitudes POST para agregar o actualizar clientes
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['add_customer'])) {
        // Agregar nuevo cliente
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "INSERT INTO customers (name, email, phone) VALUES ('$name', '$email', '$phone')";
        if ($conn->query($sql) === TRUE) {
            header("Location: ?section=customers"); // Redirige a la misma página para evitar el reenvío del formulario
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif (isset($_POST['edit_customer'])) {
        // Editar cliente existente
        $id = $_POST['id'];
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        $sql = "UPDATE customers SET name='$name', email='$email', phone='$phone' WHERE id=$id";
        if ($conn->query($sql) === TRUE) {
            header("Location: ?section=customers"); // Redirige a la misma página para evitar el reenvío del formulario
            exit();
        } else {
            echo "Error: " . $conn->error;
        }
    }
}

// Manejo de solicitudes GET para eliminar o editar clientes
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $sql = "DELETE FROM customers WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: ?section=customers"); // Redirige a la misma página para evitar el reenvío del formulario
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} elseif (isset($_GET['action']) && $_GET['action'] == 'edit') {
    $id = $_GET['id'];
    $sql = "SELECT * FROM customers WHERE id=$id";
    $result = $conn->query($sql);
    $customer = $result->fetch_assoc();
}
?>

<h1>Customers</h1>

<!-- Formulario para agregar cliente -->
<form method="post" action="">
    <h2>Add New Customer</h2>
    <label for="name">Name:</label>
    <input type="text" name="name" required>
    <label for="email">Email:</label>
    <input type="email" name="email" required>
    <label for="phone">Phone:</label>
    <input type="text" name="phone">
    <button type="submit" name="add_customer">Add Customer</button>
</form>

<!-- Formulario para editar cliente -->
<?php if (isset($customer)): ?>
<form method="post" action="">
    <h2>Edit Customer</h2>
    <input type="hidden" name="id" value="<?php echo $customer['id']; ?>">
    <label for="name">Name:</label>
    <input type="text" name="name" value="<?php echo $customer['name']; ?>" required>
    <label for="email">Email:</label>
    <input type="email" name="email" value="<?php echo $customer['email']; ?>" required>
    <label for="phone">Phone:</label>
    <input type="text" name="phone" value="<?php echo $customer['phone']; ?>">
    <button type="submit" name="edit_customer">Update Customer</button>
</form>
<?php endif; ?>

<!-- Mostrar lista de clientes -->
<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $sql = "SELECT * FROM customers";
        $result = $conn->query($sql);
        while($row = $result->fetch_assoc()):
        ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td>
                <a href="?section=customers&action=edit&id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="?section=customers&action=delete&id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
