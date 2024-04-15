<?php
session_start(); // Iniciar o continuar la sesión

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Inicio</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .table { margin-top: 20px; }
        .header { background-color: #007bff; color: white; }
        .wide-btn {
            width: 100%;             /* Makes buttons take the full width of the container */
            margin-top: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

    <div class="container">
        <h1 class="mb-4">Inicio</h1>
            <form action="Impresion_pedido.php">
                <button type="submit" name="reporte" value="ImpresionPedido" class="btn btn-info wide-btn">Impresión Pedido</button>
            </form>
            <form action="Cerrar_cuenta.php">
                <button type="submit" name="reporte" value="CerrarCuenta" class="btn btn-success wide-btn">Cerrar una cuenta</button>
            </form>
            <form action="generar_factura.php">
                <button type="submit" name="reporte" value="ImpresionFactura" class="btn btn-warning wide-btn">Impresión de factura</button>
            </form>
    </div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
<script>
// JavaScript para mostrar alertas
window.onload = function() {
    var alertMessage = "<?php echo $userAlert; ?>";
    if (alertMessage) {
        alert(alertMessage);
    }
};
</script>
</body>
</html>
