<?php
  session_start(); // Iniciar o continuar la sesión
  require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
  require 'consulta_cuentas.php';  // Asumiendo que este archivo tiene la consulta para cuentas
  require 'consulta_platos.php';  // Asumiendo consulta para platos
  require 'consulta_bebidas.php';  // Asumiendo consulta para bebidas

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
    <title>Agregar Items a Cuenta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

<div class="container mt-4">
    <h1>Agregar Items a Cuenta</h1>
    <div class="alert alert-info" role="alert">
      <?php echo $userAlert; ?>
    </div>
    <form action="envio_item_cuenta.php" method="post">
        <div class="form-group">
            <label for="tipo_cuenta">Seleccione la cuenta:</label>
            <select name="tipo_cuenta" id="tipo_cuenta" class="form-control">
              <?php while($cuenta = pg_fetch_object($consulta_cuentas)): ?>
                <option value="<?php echo htmlspecialchars($cuenta->cuenta_id); ?>">
                  <?php echo htmlspecialchars($cuenta->cuenta_id); ?>
                </option>
              <?php endwhile; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="tipo_plato">Platos:</label>
            <select name="tipo_plato" id="tipo_plato" class="form-control">
              <?php while($plato = pg_fetch_object($consulta_platos)): ?>
                <option value="<?php echo htmlspecialchars($plato->plato_id); ?>">
                  <?php echo htmlspecialchars($plato->nombre); ?>
                </option>
              <?php endwhile; ?>
            </select>
            <input type="number" id="num_platos" name="num_platos" class="form-control" min="1" max="100" required>
        </div>
        
        <div class="form-group">
            <label for="tipo_bebida">Bebidas:</label>
            <select name="tipo_bebida" id="tipo_bebida" class="form-control">
              <?php while($bebida = pg_fetch_object($consulta_bebidas)): ?>
                <option value="<?php echo htmlspecialchars($bebida->bebida_id); ?>">
                  <?php echo htmlspecialchars($bebida->nombre); ?>
                </option>
              <?php endwhile; ?>
            </select>
            <input type="number" id="num_bebida" name="num_bebida" class="form-control" min="1" max="100" required>
        </div>

        <button type="submit" class="btn btn-primary">Confirmar Items</button>
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
