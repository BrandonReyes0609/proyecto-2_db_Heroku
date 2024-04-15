<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


session_start();
require 'includes/conexion.php'; // Este archivo debe contener la creación de la conexión con la base de datos

if (!isset($_REQUEST['tipo_cuenta']) || !is_numeric($_REQUEST['tipo_cuenta'])) {
    $_SESSION['user_alert'] = "Tipo de cuenta no especificado o inválido.";
    header('Location: cerrar_cuenta.php');
    exit;
}

$tipo_cuenta = $_REQUEST['tipo_cuenta'];

// Inicia una transacción para asegurar la integridad de la data
pg_query($conn, "BEGIN");

// Consulta para obtener detalles de la cuenta y los pedidos
$query_cuentas = "SELECT cuentas.cuenta_id, cuentas.mesa_id, cuentas.fecha_apertura, cuentas.fecha_cierre, cuentas.total, items_cuenta.item_id, items_cuenta.cantidad, items_cuenta.fecha_hora, items_cuenta.cocinado, platos.plato_id, platos.nombre, platos.descripcion, platos.precio, platos.tipo, (items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $1";

// Consulta para obtener el total acumulado de la cuenta
$query_total_cuenta = "SELECT SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $1 GROUP BY cuentas.cuenta_id";

// Preparar y ejecutar las consultas
$consulta_pedidos1 = pg_prepare($conn, "detalles_cuenta", $query_cuentas);
$consulta_pedidos2 = pg_prepare($conn, "total_cuenta", $query_total_cuenta);

$resultados_detalles = pg_execute($conn, "detalles_cuenta", array($tipo_cuenta));
$resultados_total = pg_execute($conn, "total_cuenta", array($tipo_cuenta));

// Consulta para cerrar la cuenta actualizando la fecha de cierre
$query_cerrar_cuenta = "UPDATE cuentas SET fecha_cierre = NOW() WHERE cuenta_id = $1";
$resultado_cerrar_cuenta = pg_prepare($conn, "cerrar_cuenta", $query_cerrar_cuenta);
$cierre_exitoso = pg_execute($conn, "cerrar_cuenta", array($tipo_cuenta));

if ($cierre_exitoso) {
    pg_query($conn, "COMMIT"); // Completa la transacción si todo es correcto
    $_SESSION['user_alert'] = "Cuenta cerrada y datos enviados correctamente.";
} else {
    pg_query($conn, "ROLLBACK"); // Revierte la transacción en caso de error
    $_SESSION['user_alert'] = "Error al cerrar la cuenta: " . pg_last_error($conn);
    header('Location: cerrar_cuenta.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalle de la Cuenta Cerrada</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
</nav>

<div class="container">
    <h1>Detalle de la Cuenta Cerrada</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Cuenta ID</th>
                <th>Mesa ID</th>
                <th>Fecha Apertura</th>
                <th>Fecha Cierre</th>
                <th>Total</th>
                <th>Item ID</th>
                <th>Cantidad</th>
                <th>Fecha Hora</th>
                <th>Cocinado</th>
                <th>Plato ID</th>
                <th>Nombre</th>
                <th>Descripción</th>
                <th>Precio</th>
                <th>Tipo</th>
                <th>Total Item</th>
            </tr>
        </thead>
        <tbody>
            <?php while($obj = pg_fetch_object($resultados_detalles)){ ?>
            <tr>
                <td><?php echo htmlspecialchars($obj->cuenta_id); ?></td>
                <td><?php echo htmlspecialchars($obj->mesa_id); ?></td>
                <td><?php echo htmlspecialchars($obj->fecha_apertura); ?></td>
                <td><?php echo htmlspecialchars($obj->fecha_cierre); ?></td>
                <td><?php echo htmlspecialchars($obj->total); ?></td>
                <td><?php echo htmlspecialchars($obj->item_id); ?></td>
                <td><?php echo htmlspecialchars($obj->cantidad); ?></td>
                <td><?php echo htmlspecialchars($obj->fecha_hora); ?></td>
                <td><?php echo htmlspecialchars($obj->cocinado ? 'Sí' : 'No'); ?></td>
                <td><?php echo htmlspecialchars($obj->plato_id); ?></td>
                <td><?php echo htmlspecialchars($obj->nombre); ?></td>
                <td><?php echo htmlspecialchars($obj->descripcion); ?></td>
                <td><?php echo htmlspecialchars($obj->precio); ?></td>
                <td><?php echo htmlspecialchars($obj->tipo); ?></td>
                <td><?php echo htmlspecialchars($obj->total_item); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>

    <table class="table">
        <thead>
            <tr>
                <th>Total Acumulado:</th>
            </tr>
        </thead>
        <tbody>
            <?php while($total = pg_fetch_object($resultados_total)){ ?>
            <tr>
                <td><?php echo htmlspecialchars($total->sumatoria_total_items); ?></td>
            </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
window.onload = function() {
    var alertMessage = "<?php echo $_SESSION['user_alert']; ?>";
    if (alertMessage) {
        alert(alertMessage);
    }
};
</script>
</body>
</html>
