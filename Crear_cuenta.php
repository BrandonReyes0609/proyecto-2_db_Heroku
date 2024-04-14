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

// Conexión con PDO
$dsn = "pgsql:host=your_host;port=5432;dbname=your_dbname;user=your_user;password=your_password";
$conn = new PDO($dsn);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['Abrir_Cuenta'])) {
  $tipo_area1 = filter_input(INPUT_POST, 'tipo_zona', FILTER_SANITIZE_NUMBER_INT);
  $num_personas = filter_input(INPUT_POST, 'num_personas', FILTER_SANITIZE_NUMBER_INT);
  $unir_mesas = isset($_POST['unir_mesas']) ? true : false; // Asigna true si está marcado
  $numero_mesa = filter_input(INPUT_POST, 'numero_mesa', FILTER_SANITIZE_NUMBER_INT);

  try {
      // Preparar la consulta para insertar la mesa
      $query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES (:numero_mesa, :tipo_area1, :num_personas, :movilidad)";
      $stmt1 = $conn->prepare($query_mesas);
      $stmt1->bindValue(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
      $stmt1->bindValue(':tipo_area1', $tipo_area1, PDO::PARAM_INT);
      $stmt1->bindValue(':num_personas', $num_personas, PDO::PARAM_INT);
      $stmt1->bindValue(':movilidad', $unir_mesas, PDO::PARAM_BOOL);

      // Ejecutar la consulta para las mesas
      $stmt1->execute();

      // Preparar la consulta para insertar la cuenta
      $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES (:numero_mesa, NOW(), null, null)";
      $stmt2 = $conn->prepare($query_cuentas);
      $stmt2->bindValue(':numero_mesa', $numero_mesa, PDO::PARAM_INT);

      // Ejecutar la consulta para las cuentas
      $stmt2->execute();

      if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
          $userAlert = "Se enviaron los datos correctamente";
      } else {
          $userAlert = "Error al guardar los datos";
      }
  } catch (PDOException $e) {
      $userAlert = "Error en la conexión o en las consultas: " . $e->getMessage();
  }
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
<!-- El resto del código HTML sigue aquí. No olvides cerrar los tags apropiadamente y asegurarte de que los scripts y estilos están correctamente enlazados. -->
  <!-- Se continúa con el resto del código HTML y PHP -->

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
        <?php if ($userAlert != "") { echo "<p>$userAlert</p>"; } ?>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <label for="tipo_zona">Ingrese la zona:</label>
            <select name="tipo_zona" id="tipo_zona">
                <?php
                // Recibir el tipo de área desde un formulario
                $tipo_area = $_POST['tipo_zona'] ?? 1; // Default a 1 si no está definido
                $getQueryMeseros = "SELECT DISTINCT * FROM meseros WHERE area_id = :tipo_area";
                $stmt = $conn->prepare($getQueryMeseros);
                $stmt->bindParam(':tipo_area', $tipo_area, PDO::PARAM_INT);
                $stmt->execute();
                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $nombre_mesero = $row['mesero_id'];
                    $selected = ($tipo_area == $nombre_mesero) ? 'selected' : '';
                    echo "<option value='$nombre_mesero' $selected>$nombre_mesero</option>";
                }
                ?>
            </select>
            <label for="num_personas">Ingrese la cantidad de personas:</label>
            <input type="number" id="num_personas" name="num_personas" min="1" max="14">
            <label for="numero_mesa">Ingrese No. mesa:</label>
            <input type="number" id="numero_mesa" name="numero_mesa">
            <input type="checkbox" id="unir_mesas" name="unir_mesas" checked>
            <label for="unir_mesas">Unir mesas</label>
            <br>
            <input type="submit" name="Abrir_Cuenta" value="Abrir Cuenta">
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