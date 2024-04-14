<?php
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Crear la cadena de conexión
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";

// Establecer conexión
$conn = pg_connect($dsn);

$tipo_area1 = $_REQUEST['tipo_zona'];
$num_personas = $_REQUEST['num_personas'];
$numero_mesa = $_REQUEST['numero_mesa'];

$query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES ($1, $2, $3, false)";
$resultado1 = pg_query_params($conn, $query_mesas, array($numero_mesa, $tipo_area1, $num_personas));

if ($resultado1) {
    $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES ($1, NOW(), null, 0)";
    $resultado2 = pg_query_params($conn, $query_cuentas, array($numero_mesa));

    if ($resultado2) {
        echo "<h3>Se enviaron los datos correctamente</h3>";
    } else {
        echo "<h3>Error resultado 2: " . pg_last_error($conn) . "</h3>";
    }
} else {
    echo "<h3>Error resultado 1: " . pg_last_error($conn) . "</h3>";
}

pg_close($conn);
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
