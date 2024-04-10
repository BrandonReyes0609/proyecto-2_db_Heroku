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
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="home-container">
        <h1>Bienvenido al Sistema</h1>
        <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
        <p><a href="logout.php">Cerrar Sesión</a></p>
    </div>
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
