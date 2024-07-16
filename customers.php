<?php
include 'database.php';

// Obtener lista de clientes
$sql = "SELECT * FROM customers";
$result = $conn->query($sql);
?>

<h1>Customers</h1>
<a href="add_customer.php">Add New Customer</a>
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
        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?php echo $row['id']; ?></td>
            <td><?php echo $row['name']; ?></td>
            <td><?php echo $row['email']; ?></td>
            <td><?php echo $row['phone']; ?></td>
            <td>
                <a href="edit_customer.php?id=<?php echo $row['id']; ?>">Edit</a> |
                <a href="delete_customer.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure?');">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
