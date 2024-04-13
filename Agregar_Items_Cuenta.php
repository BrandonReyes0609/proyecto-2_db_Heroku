<?php
session_start(); // Iniciar o continuar la sesión

// Redirige al usuario a 'index.php' si no está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit; // Detiene la ejecución del script después de la redirección
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
    <title>Agregar Item Cuenta</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>

<div class="home-container">
    <h1>Bienvenido al Sistema</h1>
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <form action="#.php">
      <label for="lang">Ingrese la zona:</label>
      <select name="tipo_zona" id="tipo_zona">
        <option value="zona1">zona 1</option>
        <option value="zona2">zona 2</option>
        <option value="zona3">zona 3</option>
        
      <input type="checkbox" id="unir_mesa" name="unir_mesa" value="">
      <span>Unir mesas</span>
      <label for="lang">Ingrese No. mesa:</label>
      <input type="text" id="numero_mesa" name="numero_mesa" value="">
      <label for="lang">Ingrese la cantidad de personas:</label>
      <input type="text" id="num_personas" name="num_personas" value="">
      <input type="submit" value="Abrir Cuenta">

      <br>
    </form>
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
