<?php
  session_start(); // Iniciar o continuar la sesión

  require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
  require 'Consulta_cuenta_cerrada.php';
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

  // Si se envió el formulario, insertar los datos del cliente en la tabla cliente

  echo($_POST['tipo_cuenta'])
  echo($_POST['tipo_cuenta'])
  echo($_POST['tipo_cuenta'])
  echo($_POST['tipo_cuenta'])
  echo($_POST['tipo_cuenta'])
  $cuenta_id = $_POST['tipo_cuenta'];
  echo($cuenta_id);
  $nombre_cliente = $_POST['nombre_cliente'];
  $nit_cliente = $_POST['nit_cliente'];
  $direccion_cliente = $_POST['direccion_cliente'];
  $metodo_pago = $_POST['metodo_pago'];
  // Preparar la consulta SQL para insertar los datos del cliente en la tabla cliente
  $sql_insert_cliente = "INSERT INTO cliente (cuenta_id,nombre, nit, direccion) VALUES ($1, $2, $3, $4)";
  //$insert_encuesta_meseros = "INSERT INTO encuesta_mesero (mesero_id, puntuacion_amabilidad, puntuacion_exactitud, fecha_encuesta)VALUES ($1, $2, $3, NOW())";
  $params1 = array($cuenta_id,$nombre_cliente, $nit_cliente, $direccion_cliente);
  $resultado1 = pg_query_params($conn, $sql_insert_cliente, $params1);

  // Ejecutar la consulta
  if ($resultado1 === false) {
    echo "Error en la consulta 1: " . pg_last_error($conn);
} else {
    echo "Registro insertado correctamente 1.";

    $sql_insert_cliente = "INSERT INTO cliente (cuenta_id,metodo, null) VALUES ($1, $2, $3)";
    $params2 = array($cuenta_id, $metodo);
    $resultado1 = pg_query_params($conn, $sql_insert_cliente, $params2);

    if ($resultado1 === false) {
      echo "Error en la consulta 1: " . pg_last_error($conn);
  } else {
      echo "Registro insertado correctamente 1.";
  }
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
    <h1>Impresión de Facturas</h1>

    <!-- Formulario para ingresar datos del cliente -->
    <form action="generar_factura.php" method="get">
      <span>Seleccione la cuenta:</span>
      <select name="tipo_cuenta" id="tipo_cuenta">
          <?php
            require 'Consulta_cuenta_cerrada.php';

            while($obj = pg_fetch_object($consulta)){?>
              <option value="<?php echo ($obj->cuenta_id) ?>"><?php echo($obj->cuenta_id);?></option>
            <?php

            }
          ?>
        </select>
        

      <!-- Campos para ingresar datos del cliente -->
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
      

      <!-- Botón para enviar el formulario -->
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
