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
            <!--END TOP-->

            <div class="sidebar">
                <a href="#" class="menu-link" data-target="dashboard">
                    <span class="material-symbols-outlined">dashboard</span>
                    <h3>Dashboard</h3> 
                </a>
                <a href="#" class="menu-link" data-target="customers">
                    <span class="material-symbols-outlined">person</span>
                    <h3>Customers</h3> 
                </a>
                <!-- Otros enlaces -->
                <a href="#" id="logoutButton" class="menu-link">
                    <span class="material-symbols-outlined">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>
        <!-- aside section end-->

        <!-- main section start-->
        <main>
            <div id="dashboard" class="content-section active">
                <h1>Dashboard</h1>
                <div class="date">
                    <input type="date">
                </div>
                <!-- Más contenido aquí -->
            </div>

            <div id="customers" class="content-section">
                <h1>Customers</h1>
                <form id="customerForm">
                    <input type="hidden" id="customerId" name="id">
                    <input type="text" id="customerName" name="name" placeholder="Name" required>
                    <input type="email" id="customerEmail" name="email" placeholder="Email" required>
                    <button type="submit">Save</button>
                </form>

                <table id="customersTable">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Dynamic rows will be appended here -->
                    </tbody>
                </table>
            </div>

            <!-- Más secciones aquí -->
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
