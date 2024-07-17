<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$inactive = 1800;
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    session_unset();
    session_destroy();
    header("Location: index.php");
    exit();
}
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>App Bitácora</title>
    <link rel="stylesheet" href="css/style.css">
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
            <div id="dashboard" class="content-section active">
                <h1>Dashboard</h1>
                <div class="date">
                    <input type="date">
                </div>
                <div class="insights">
                    <div class="sales">
                        <span class="material-symbols-outlined"></span>
                    </div>
                </div>
            </div>

            <div id="customers" class="content-section">
                <!-- Content loaded via AJAX -->
            </div>

            <div id="analytics" class="content-section">
                <h1>Analytics</h1>
            </div>

            <div id="messages" class="content-section">
                <h1>Messages</h1>
            </div>

            <div id="products" class="content-section">
                <h1>Products</h1>
            </div>

            <div id="reports" class="content-section">
                <h1>Reports</h1>
            </div>

            <div id="settings" class="content-section">
                <h1>Settings</h1>
            </div>

            <div id="add-product" class="content-section">
                <h1>Add Product</h1>
            </div>
        </main>
        <!-- main section end-->

        <!-- right section start-->
        <div class="right">
            <h1>Right</h1>
        </div>
        <!-- right section end-->
    </div>
    <script src="js/script.js"></script>
</body>
</html>
