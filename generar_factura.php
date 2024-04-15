<?php
  session_start(); // Iniciar o continuar la sesión

  require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
  require 'consulta_cunetas.php';
  //require 'Consulta_items_cuenta.php';



  // Verificar si el usuario está autenticado
  if (!isset($_SESSION['nombre_usuario'])) {
      header("Location: index.php");
      exit;
  }

  // Almacenar mensaje de alerta en una variable y limpiar la sesión
  $userAlert = "";
  if (isset($_SESSION['user_alert'])) {
      $userAlert = $_SESSION['user_alert'];
      unset($_SESSION['user_alert']); // Limpiar esa variable de sesión después de usarla
  }

  ?>
  
  
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Generación de Factura</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

<div class="home-container">
    <h1>Generación de Factura</h1>

    <form action="factura.php" method="get">
      <div class="form-group">
        <label for="cuenta_id">Seleccione la cuenta:</label>
        <select name="cuenta_id" id="cuenta_id" class="form-control">
          <?php
            // Asegúrate de que esta consulta sea segura y solo incluya cuentas cerradas
            $consulta = pg_query($conn, "SELECT cuenta_id FROM cuentas WHERE estado = 'cerrada'");
            while($obj = pg_fetch_object($consulta)){
                echo '<option value="'.htmlspecialchars($obj->cuenta_id).'">'.htmlspecialchars($obj->cuenta_id).'</option>';
            }
          ?>
        </select>
      </div>
      
      <div class="form-group">
        <label for="nombre_cliente">Nombre del Cliente:</label>
        <input type="text" name="nombre_cliente" id="nombre_cliente" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="nit_cliente">NIT del Cliente:</label>
        <input type="text" name="nit_cliente" id="nit_cliente" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="direccion_cliente">Dirección del Cliente:</label>
        <input type="text" name="direccion_cliente" id="direccion_cliente" class="form-control" required>
      </div>

      <div class="form-group">
        <label for="metodo_pago">Método de Pago:</label>
        <select name="metodo_pago" id="metodo_pago" class="form-control">
          <option value="Efectivo">Efectivo</option>
          <option value="Tarjeta">Tarjeta</option>
          <option value="Transferencia">Transferencia</option>
          <!-- Agrega más métodos de pago según sea necesario -->
        </select>
      </div>

      <button type="submit" class="btn btn-primary">Generar Factura</button>
    </form>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
// JavaScript para mostrar la alerta
window.onload = function() {
    var alertMessage = "<?php echo $userAlert; ?>";
    if (alertMessage) {
        alert(alertMessage);
    }
};
</script>
</body>
</html>
