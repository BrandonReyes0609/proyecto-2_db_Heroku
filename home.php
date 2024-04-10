<?php
session_start(); // Iniciar o continuar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    // Si no hay sesión de usuario, redirigir a la página de inicio de sesión
    header("Location: index.php");
    exit;
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
</body>
</html>
