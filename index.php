<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Formulario Sencillo</title>
</head>
<body>
    <?php
    // Definir variables y establecerlas vacías
    $nombre = $email = $mensajeError = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["nombre"]) || empty($_POST["email"])) {
            $mensajeError = "Todos los campos son obligatorios.";
        } else {
            $nombre = limpiarDatos($_POST["nombre"]);
            $email = limpiarDatos($_POST["email"]);

            // Validar dirección de correo electrónico
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $mensajeError = "Formato de correo electrónico inválido.";
            } else {
                // Mostrar los datos enviados
                echo "<h2>Datos enviados:</h2>";
                echo "Nombre: " . $nombre . "<br>";
                echo "Email: " . $email . "<br>";
            }
        }
    }

    function limpiarDatos($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>

    <h2>Formulario de Contacto</h2>
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        Nombre: <input type="text" name="nombre" value="<?php echo $nombre;?>">
        <br><br>
        Email: <input type="text" name="email" value="<?php echo $email;?>">
        <br><br>
        <input type="submit" name="submit" value="Enviar">
    </form>
    
    <span style="color:red;"><?php echo $mensajeError;?></span>
</body>
</html>
