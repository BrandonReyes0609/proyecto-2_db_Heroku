<?php
session_start(); // Iniciar o continuar la sesión

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

// Detalles de la base de datos
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Crear la cadena de conexión
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";

// Establecer conexión
$conn = pg_connect($dsn);
if (!$conn) {
    error_log('Error de conexión: ' . pg_last_error());
    die("Error de conexión con la base de datos");
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
    <h1>Agregar Items</h1>
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <form action="#.php">
    <label for="lang">Selecciones la cuenta:</label>
        <select name="tipo_cuenta" id="tipo_cuenta">
            <?php
              $quey_cuentas = "SELECT * FROM cuentas";
              $consulta_cuentas = pg_query($conn, $quey_cuentas);
              if (!$consulta_cuentas) {
                  error_log('Error en consulta SQL: ' . pg_last_error());
                  echo '<option>Error al cargar cuentas</option>';
              } else {
                  while ($obj = pg_fetch_object($consulta_cuentas)) {
                      echo '<option value="' . htmlspecialchars($obj->cuenta_id) . '">' . htmlspecialchars($obj->nombre) . '</option>';
                  }
              }
            ?>
        </select>

        <label for="lang">Platos:</label>
        <select name="tipo_plato" id="tipo_plato">
            <option value="plato1">Plato 1</option>
            <option value="plato2">Plato 2</option>
            <option value="plato3">Plato 3</option>
          </select>
        <input type="number" id="num_platos" name="num_platos" min="1" max="100" placeholder="Cantidad de platos" />

        <label for="lang">Bebidas:</label>
        <select name="tipo_bebida" id="tipo_bebida">
            <option value="bebida1">Bebida 1</option>
            <option value="bebida2">Bebida 2</option>
            <option value="bebida3">bebida 3</option>
        </select>
        <input type="number" id="num_bebida" name="num_bebida" min="1" max="100" placeholder="Cantidad de bebidas" />

        <br>
        <input type="submit" value="Confirma Comida">
        <br>
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
