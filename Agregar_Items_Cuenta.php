<?php
  session_start(); // Iniciar o continuar la sesión

  require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
  require 'consulta_cunetas.php';  // Asumiendo que este archivo tiene la conexión y la consulta
  require 'consulta_platos.php';  
  require 'consulta_bebidas.php';  


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
      <title>Agregar Items</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/estilos.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
  </nav>

  <div class="home-container">
      <h1>Agregar Items</h1>

      <form action="Envio_item_cuenta.php">
      <label for="tipo_cuenta">Seleccione la cuenta:</label>
        <select name="tipo_cuenta" id="tipo_cuenta">
          <?php
            while($obj = pg_fetch_object($consulta)){?>
              <option value="<?php echo ($obj->cuenta_id) ?>"><?php echo($obj->cuenta_id);?></option>
            <?php
            }
          ?>
        </select>
        
          <label for="tipo_plato">Platos:</label>
          <select name="tipo_plato" id="tipo_plato">
            <?php
              while($obj = pg_fetch_object($consulta_platos)){?>
                <option value="<?php echo ($obj->plato_id) ?>"><?php echo($obj->nombre);?></option>
              <?php
              }
            ?>
          </select>
          <input type="number" id="num_platos" name="num_platos" min="1" max="100" />
          
          <label for="tipo_bebida">Bebidas:</label>
          <select name="tipo_bbeida" id="tipo_bebida">
          <?php
              while($obj = pg_fetch_object($consulta_bebidas)){?>
                <option value="<?php echo ($obj->plato_id) ?>"><?php echo($obj->nombre);?></option>
              <?php
              }
            ?>
            </select>
          <input type="number" id="num_bebida" name="num_bebida" min="1" max="100" />

          <br>
          <input type="submit" value="Confirma_Comida">
          <br>
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