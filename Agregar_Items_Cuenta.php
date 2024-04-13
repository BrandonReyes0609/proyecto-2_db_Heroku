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

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $texto = $_POST['texto'];
    $cantidadTexto = $_POST['cantidadTexto'];
    $listaTexto = array_fill(0, $cantidadTexto, $texto);

    $tipoPlato = $_POST['tipo_plato'];
    $numPlatos = $_POST['num_platos'];
    $listaPlatos = array_fill(0, $numPlatos, $tipoPlato);

    $tipoBebida = $_POST['tipo_bebida'];
    $numBebidas = $_POST['num_bebida'];
    $listaBebidas = array_fill(0, $numBebidas, $tipoBebida);

    // Mensaje de confirmación
    $_SESSION['user_alert'] = "Items agregados correctamente.";
    header("Location: " . $_SERVER['PHP_SELF']);
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
<!-- Código anterior del navbar y demás se mantiene -->
<!-- Asegúrate de incluir el código anterior para la navegación aquí -->

<div class="home-container">
    <h1>Agregar Items</h1>
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <!-- Formulario actualizado para enviar datos a este mismo archivo -->
    <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
        <label for="texto">Texto a multiplicar:</label>
        <input type="text" id="texto" name="texto" required>
        <input type="number" id="cantidadTexto" name="cantidadTexto" min="1" required>
        
        <label for="tipo_plato">Platos:</label>
        <select name="tipo_plato" id="tipo_plato">
            <!-- Opciones de platos se mantienen -->
        </select>
        <input type="number" id="num_platos" name="num_platos" min="1" max="100" />

        <label for="tipo_bebida">Bebidas:</label>
        <select name="tipo_bebida" id="tipo_bebida">
            <!-- Opciones de bebidas se mantienen -->
        </select>
        <input type="number" id="num_bebida" name="num_bebida" min="1" max="100" />

        <input type="submit" value="Confirmar Items">
    </form>
</div>

<!-- Código anterior de JS y Bootstrap se mantiene -->
<!-- Asegúrate de incluir el código anterior para JS y Bootstrap aquí -->

<script>
// Código de JavaScript para manejar la alerta
window.onload = function() {
    var alertMessage = "<?php echo $userAlert; ?>";
    if (alertMessage) {
        alert(alertMessage);
    }
};
</script>
</body>
</html>
