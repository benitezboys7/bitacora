<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.html");
    exit();
}

$inactive = 1800; // Tiempo en segundos (30 minutos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    // Última actividad fue hace más de 30 minutos
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: index.html"); // Redirige al usuario al inicio de sesión
    exit();
}
$_SESSION['last_activity'] = time(); // Actualiza el tiempo de la última actividad
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Bitácora</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div class="container">
        <!-- aside section start-->
        <aside>
            <div class="top">
                <div class="logo">
                    <h2>ADMIS <span class="danger">S.A.S.</span></h2>
                </div>
                <div class="close">
                    <span class="material-symbols-outlined">close</span>
                </div>
            </div>
            <!-- END TOP -->

            <div class="sidebar">
                <a href="#" class="menu-link" data-target="dashboard">
                    <span class="material-symbols-outlined">dashboard</span>
                    <h3>Dashboard</h3> 
                </a>
                <a href="#" class="menu-link" data-target="customers">
                    <span class="material-symbols-outlined">person</span>
                    <h3>Customers</h3> 
                </a>
                <a href="#" class="menu-link" data-target="analytics">
                    <span class="material-symbols-outlined">insights</span>
                    <h3>Analytics</h3> 
                </a>
                <a href="#" class="menu-link" data-target="messages">
                    <span class="material-symbols-outlined">mail_outline</span>
                    <h3>Messages</h3> 
                    <span class="msg_count">14</span>
                </a>
                <a href="#" class="menu-link" data-target="products">
                    <span class="material-symbols-outlined">receipt_long</span>
                    <h3>Products</h3> 
                </a>
                <a href="#" class="menu-link" data-target="reports">
                    <span class="material-symbols-outlined">report_gmailerrorred</span>
                    <h3>Reports</h3> 
                </a>
                <a href="#" class="menu-link" data-target="settings">
                    <span class="material-symbols-outlined">settings</span>
                    <h3>Settings</h3> 
                </a>
                <a href="#" class="menu-link" data-target="add-product">
                    <span class="material-symbols-outlined">add</span>
                    <h3>Add Product</h3> 
                </a>
                <a href="#" id="logoutButton" class="menu-link">
                    <span class="material-symbols-outlined">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- aside section end-->

        <!-- main section start-->
        <main>
            <div id="dashboard" class="content-section">
                <h1>Dashboard</h1>
                <div class="date">
                    <input type="date">
                </div>
                <div class="insights">
                    <!--start selling-->
                    <div class="sales">
                        <span class="material-symbols-outlined"></span>
                    </div>
                </div>
            </div>

            <div id="customers" class="content-section">
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

            </div>

            <div id="analytics" class="content-section">
                <h1>Analytics</h1>
                <!-- Contenido de Analytics -->
            </div>

            <div id="messages" class="content-section">
                <h1>Messages</h1>
                <!-- Contenido de Messages -->
            </div>

            <div id="products" class="content-section">
                <h1>Products</h1>
                <!-- Contenido de Products -->
            </div>

            <div id="reports" class="content-section">
                <h1>Reports</h1>
                <!-- Contenido de Reports -->
            </div>

            <div id="settings" class="content-section">
                <h1>Settings</h1>
                <!-- Contenido de Settings -->
            </div>

            <div id="add-product" class="content-section">
                <h1>Add Product</h1>
                <!-- Contenido de Add Product -->
            </div>
        </main>
        <!-- main section end-->

        <!-- right section start-->
        <div class="right">
            <h1>Right</h1>
        </div>
        <!-- right section end-->
    </div>
    <script src="script.js"></script>
</body>
</html>
