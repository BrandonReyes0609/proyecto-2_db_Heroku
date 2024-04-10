<?php
include 'includes/conexion.php';

session_start(); // Iniciar la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_usuario']) && isset($_POST['contraseña'])) {
    // Recuperar credenciales del formulario
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $contraseña = trim($_POST['contraseña']);

    // Preparar la consulta para buscar al usuario por su ID
    $stmt = $conn->prepare("SELECT nombre_usuario, contraseña FROM users WHERE nombre_usuario = :nombre_usuario");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if (password_verify($contraseña, $user['contraseña'])) {
            // Credenciales correctas, establecer variables de sesión
            $_SESSION['nombre_usuario'] = $user['nombre_usuario'];

            // Redirigir al usuario a la página principal (home.php)
            header("Location: home.php");
            exit;
        } else {
            // Credenciales incorrectas
            $error = "Usuario o contraseña incorrectos.";
        }
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }

    // Cerrar la conexión
    cerrarConexion();
} else {
    $error = "Por favor complete todos los campos.";
}

// Mostrar el error en caso de que exista (deberías pasar este mensaje a `index.php` de manera segura, posiblemente con sesiones)
if (isset($error)) {
    echo $error;
}
?>
