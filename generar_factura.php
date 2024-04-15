<?php
require_once 'includes/conexion.php';


?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Seleccionar Cuenta Cerrada para Factura</title>
</head>
<body>
    <h1>Seleccionar Cuenta Cerrada para Factura</h1>
    <form action="factura.php" method="get">
        <label for="cuenta_id">Cuenta ID:</label>
        <select name="cuenta_id" id="cuenta_id">
            <?php
            // Consulta para obtener solo cuentas cerradas
            $stmt = $conn->query("SELECT cuenta_id FROM cuentas WHERE fecha_cierre = NULL");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo '<option value="' . htmlspecialchars($row['cuenta_id']) . '">' . htmlspecialchars($row['cuenta_id']) . '</option>';
            }
            ?>
        </select>
        <input type="submit" value="Generar Factura">
    </form>
</body>
</html>
