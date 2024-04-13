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

// Asegurar que los datos POST estén disponibles antes de intentar acceder a ellos
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['tipo_zona'])) {
        echo htmlspecialchars($_POST['tipo_zona']) . "<br>";
    }
    if (isset($_POST['unir_mesa'])) {
        echo htmlspecialchars($_POST['unir_mesa']) . "<br>";
    }
    if (isset($_POST['num_personas'])) {
        echo htmlspecialchars($_POST['num_personas']) . "<br>";
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
<!-- Resto del código HTML sigue igual -->
</body>
</html>
