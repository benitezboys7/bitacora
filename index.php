<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <?php
        $version = time(); // Utiliza la fecha y hora actual para la versión
    ?>
    <link rel="stylesheet" href="login-styles.css?v=<?php echo $version; ?>">
</head>
<body>
    <div class="login-register-container">
        <h2>Login</h2>
        <form id="loginForm">
            <div>
                <label for="loginEmail">Email:</label>
                <input type="email" id="loginEmail" name="email" required>
            </div>
            <div>
                <label for="loginPassword">Password:</label>
                <input type="password" id="loginPassword" name="password" required>
            </div>
            <button type="submit">Login</button>
        </form>
    </div>
    <div id="messageContainer" class="hidden"></div>
    <script src="script.js?v=<?php echo $version; ?>"></script>
</body>
</html>
