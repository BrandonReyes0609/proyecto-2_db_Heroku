<?php
session_start();
require 'includes/conexion.php'; // Este archivo debería manejar la creación de la conexión
require 'consulta_cuentas.php';

if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

$userAlert = $_SESSION['user_alert'] ?? '';
unset($_SESSION['user_alert']);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cerrar Cuenta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

<div class="container">
    <h1>Cerrar Cuenta</h1>
    <form action="cerrar_cuenta_2.php" method="post">
        <div class="form-group">
            <label for="tipo_cuenta">Seleccione la cuenta:</label>
            <select name="tipo_cuenta" id="tipo_cuenta" class="form-control">
                <?php
                $consulta = pg_query($conn, $query_cuentas_abiertas);
                while ($fila = pg_fetch_assoc($consulta)) {
                    echo "<option value='{$fila['cuenta_id']}'>Cuenta {$fila['cuenta_id']}</option>";
                }
                ?>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Cerrar Cuenta</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
window.onload = function() {
    if ("<?php echo $userAlert; ?>") {
        alert("<?php echo $userAlert; ?>");
    }
};
</script>
</body>
</html>
