<?php
session_start(); // Iniciar o continuar la sesión
echo 'Rol actual en la sesión: ' . (isset($_SESSION['rol']) ? $_SESSION['rol'] : 'No definido');

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
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
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <form action="#.php">
    <label for="lang">Selecciones la cuenta:</label>
        <select name="tipo_cuenta" id="tipo_cuenta">
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
