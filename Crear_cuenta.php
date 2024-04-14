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

// Procesar el formulario al hacer clic en 'Abrir Cuenta'
if (isset($_POST['Abrir_Cuenta'])) {
    $tipo_area1 = $_POST['tipo_zona']; // Tipo de área a asignar
    $num_personas = $_POST['num_personas']; // Número de personas
    $unir_mesas = $_POST['unir_mesas']; // Check de unir mesas
    $numero_mesa = $_POST['numero_mesa']; // Número de mesa

    try {
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
    <p><?php echo $userAlert; ?></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <label for="tipo_zona">Ingrese la zona:</label>
      <select name="tipo_zona" id="tipo_zona">
        <?php
        // Si ya se seleccionó una zona, volver a cargar las opciones de zona
        $stmt->execute();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre_mesero = $row['mesero_id'];
            $selected = ($nombre_mesero == $tipo_area) ? ' selected' : '';
            echo "<option value='$nombre_mesero'$selected>$nombre_mesero</option>";
        }
        ?>
      </select>

      <label for="num_personas">Ingrese la cantidad de personas:</label>
      <input type="number" id="num_personas" name="num_personas" min="1" max="14" required>

      <label for="numero_mesa">Ingrese No. mesa:</label>
      <input type="number" id="numero_mesa" name="numero_mesa" required>

      <input type="checkbox" id="unir_mesas" name="unir_mesas">
      <label for="unir_mesas">Unir mesas</label>

      <input type="submit" name="Abrir_Cuenta" value="Abrir Cuenta">
    </form>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="assets/js/scripts.js"></script>
<script>
    // JavaScript para mostrar la alerta
    window.onload = function() {
        if ("<?php echo $userAlert; ?>") {
            alert("<?php echo $userAlert; ?>");
        }
    };
</script>
</body>
</html>