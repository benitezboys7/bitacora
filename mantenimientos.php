<?php
session_start(); // Inicia la sesión

// Verifica si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // Redirige al inicio de sesión si no hay una sesión activa
    header("Location: index.php");
    exit();
}

$inactive = 1800; // Tiempo en segundos (30 minutos)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $inactive) {
    // Última actividad fue hace más de 30 minutos
    session_unset(); // Elimina todas las variables de sesión
    session_destroy(); // Destruye la sesión
    header("Location: index.php"); // Redirige al usuario al inicio de sesión
    exit();
}
$_SESSION['last_activity'] = time(); // Actualiza el tiempo de la última actividad
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Products CRUD</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />
</head>
<body>
    <div id="products" class="content-section">
        <h1>Products</h1>
        <a href="#" class="add-button" id="addProductBtn">Add New Product</a>

        <!-- Modal para agregar/editar producto -->
        <div id="productModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalTitle">Agregar Nuevo Producto</h2>
                <form id="productForm" method="post" action="save_product.php">
                    <input type="hidden" id="productId" name="id">
                    <label for="productName">Nombre Mantenimiento:</label>
                    <input type="text" id="productName" name="nombre_mantenimiento" required>

                    <label for="productQuantity">Cantidad:</label>
                    <input type="number" id="productQuantity" name="cantidad" required>

                    <label for="productProvider">Proveedor:</label>
                    <select id="productProvider" name="proveedor" required>
                        <!-- Opciones cargadas mediante JavaScript -->
                    </select>

                    <label for="productContract">Contrato:</label>
                    <select id="productContract" name="contrato" required>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>

                    <label for="productWarranty">Garantía:</label>
                    <select id="productWarranty" name="garantia" required>
                        <option value="SI">SI</option>
                        <option value="NO">NO</option>
                    </select>

                    <label for="contractStartDate">Fecha Inicio Contrato:</label>
                    <input type="date" id="contractStartDate" name="fecha_inicio_contrato" required>

                    <label for="contractEndDate">Fecha Vencimiento Contrato:</label>
                    <input type="date" id="contractEndDate" name="fecha_vencimiento_contrato" required>

                    <label for="productPeriodicity">Periodicidad:</label>
                    <select id="productPeriodicity" name="periodicidad" required>
                        <option value="Mensual">Mensual</option>
                        <option value="Bimensual">Bimensual</option>
                        <option value="Trimestral">Trimestral</option>
                        <option value="Semestral">Semestral</option>
                        <option value="Anual">Anual</option>
                    </select>

                    <label for="lastMaintenanceDate">Fecha Último Mantenimiento:</label>
                    <input type="date" id="lastMaintenanceDate" name="fecha_ultimo_mantenimiento" required>

                    <fieldset>
                        <legend>Adicional:</legend>
                        <label><input type="checkbox" name="adicional[]" value="Enero"> Enero</label>
                        <label><input type="checkbox" name="adicional[]" value="Febrero"> Febrero</label>
                        <label><input type="checkbox" name="adicional[]" value="Marzo"> Marzo</label>
                        <label><input type="checkbox" name="adicional[]" value="Abril"> Abril</label>
                        <label><input type="checkbox" name="adicional[]" value="Mayo"> Mayo</label>
                        <label><input type="checkbox" name="adicional[]" value="Junio"> Junio</label>
                        <label><input type="checkbox" name="adicional[]" value="Julio"> Julio</label>
                        <label><input type="checkbox" name="adicional[]" value="Agosto"> Agosto</label>
                        <label><input type="checkbox" name="adicional[]" value="Septiembre"> Septiembre</label>
                        <label><input type="checkbox" name="adicional[]" value="Octubre"> Octubre</label>
                        <label><input type="checkbox" name="adicional[]" value="Noviembre"> Noviembre</label>
                        <label><input type="checkbox" name="adicional[]" value="Diciembre"> Diciembre</label>
                    </fieldset>

                    <button type="submit">Guardar Producto</button>
                </form>
            </div>
        </div>
        
        <!-- Aquí puedes agregar una tabla o lista para mostrar los productos -->
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre Mantenimiento</th>
                    <th>Cantidad</th>
                    <th>Proveedor</th>
                    <th>Contrato</th>
                    <th>Garantía</th>
                    <th>Fecha Inicio Contrato</th>
                    <th>Fecha Vencimiento Contrato</th>
                    <th>Periodicidad</th>
                    <th>Fecha Último Mantenimiento</th>
                    <th>Adicional</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <!-- Filas de productos se agregarán aquí mediante JavaScript -->
            </tbody>
        </table>
    </div>

    <script src="script_mantenimientos.js"></script>
    <script src="script.js"></script>
</body>
</html>