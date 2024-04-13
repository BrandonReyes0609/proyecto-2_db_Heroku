<?php
session_start(); // Iniciar o continuar la sesión

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
