<?php
session_start(); // Iniciar o continuar la sesión

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

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
    <title>Bienvenido al Sistema</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <a class="navbar-brand" href="#">Mi Sitio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Crear_cuenta.php">Crear Cuenta</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Agregar_Items_Cuenta.php">Items Cuenta</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Pantalla_cocina.php">Pantalla Cocina</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#more">Más</a>
      </li>
    </ul>
  </div>
</nav>

<div class="home-container">
    <h1>Bienvenido al Sistema</h1>
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>
    <p><?php echo $_POST['tipo_zona']; ?></p>
    <p><?php echo $_POST['unir_mesa']; ?></p>
    <p><?php echo $_POST['num_personas']; ?></p>

    <form action="Crear_cuenta.php" method="post">
      <label for="tipo_zona">Ingrese la zona:</label>
      <select name="tipo_zona" id="tipo_zona">
        <!-- Opciones de zona añadidas aquí -->
        <?php
        // Recibir el tipo de área desde un formulario
        $tipo_area = $_POST['tipo_zona'] ?? 1; // Default a 1 si no está definido

        // Preparar la consulta SQL
        $getQueryMeseros = "SELECT DISTINCT * FROM meseros WHERE area_id = :tipo_area";
        $stmt = $conn->prepare($getQueryMeseros);
        $stmt->bindParam(':tipo_area', $tipo_area, PDO::PARAM_INT);

        // Ejecutar la consulta


        // Ejecutar la consulta
        $stmt->execute();

        // Iterar sobre los resultados y generar el HTML para las opciones del dropdown
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre_mesero = $row['mesero_id'];
            echo "<option value='$nombre_mesero'>$nombre_mesero</option>";
        }
        ?>
      </select>

      <label for="num_personas">Ingrese la cantidad de personas:</label>
      <input type="number" id="num_personas" name="num_personas" value="" min="1" max="14">

      <label for="numero_mesa">Ingrese No. mesa:</label>
      <input type="number" id="numero_mesa" name="numero_mesa" value="">

      <!-- Checkbox para habilitar/deshabilitar todos los inputs -->
      <input type="checkbox" id="unir_mesas" checked>
      <span>Unir mesas</span>

      <!-- Inputs que serán habilitados/deshabilitados -->
      <input type="number" id="miInput1" disabled min="1" max="20">
      <input type="number" id="miInput2" disabled min="1" max="20">
      <input type="number" id="miInput3" disabled min="1" max="20">

      <script>
          // Script para controlar el estado de los inputs
          document.getElementById('unir_mesas').addEventListener('change', function() {
              var isChecked = this.checked; // Verificar si el checkbox está marcado
              document.getElementById('miInput1').disabled = !isChecked;
              document.getElementById('miInput2').disabled = !isChecked;
              document.getElementById('miInput3').disabled = !isChecked;
          });
      </script>
      <br>
      <input type="submit" value="Abrir Cuenta">  
    </form>
    <?php
      if (isset($_POST['Abrir_Cuenta'])) {
          $tipo_area1 = $_POST['tipo_zona']; //tipo de área a asignar
          $num_personas = $_POST['num_personas']; // número de personas
          $unir_mesas = $_POST['unir_mesas']; // check de unir mesas
          $numero_mesa = $_POST['numero_mesa']; // número de mesa

          // Conexión con PDO
          $dsn = "pgsql:host=your_host;port=5432;dbname=your_dbname;user=your_user;password=your_password";
          try {
              $conn = new PDO($dsn);
              $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

              // Preparar la consulta para insertar la mesa
              $query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES (:numero_mesa, :tipo_area1, :num_personas, false)";
              $stmt1 = $conn->prepare($query_mesas);
              $stmt1->bindParam(':numero_mesa', $numero_mesa);
              $stmt1->bindParam(':tipo_area1', $tipo_area1);
              $stmt1->bindParam(':num_personas', $num_personas);

              // Ejecutar la consulta para las mesas
              $stmt1->execute();

              // Preparar la consulta para insertar la cuenta
              $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES (:numero_mesa, NOW(), null, null)";
              $stmt2 = $conn->prepare($query_cuentas);
              $stmt2->bindParam(':numero_mesa', $numero_mesa);

              // Ejecutar la consulta para las cuentas
              $stmt2->execute();

              if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
                  ?>
                  <h3>Se enviaron los datos correctamente</h3>
                  <?php
              } else {
                  ?>
                  <h3>Error al guardar los datos</h3>
                  <?php
              }
          } catch (PDOException $e) {
              die("Error en la conexión o en las consultas: " . $e->getMessage());
          }
      }
    ?>
    <h2>Asignar mesero</h2>
    <select>
      <?php
        // Reutilizamos el código de consulta anterior para generar otra lista de opciones de meseros
        $stmt->execute(); // Re-ejecutar la misma consulta
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre_mesero = $row['mesero_id'];
            echo "<option value='$nombre_mesero'>$nombre_mesero</option>";
        }
      ?>
    </select>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
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