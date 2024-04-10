<?php
include 'includes/conexion.php';

session_start(); // Iniciar la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    // Recuperar credenciales del formulario
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // Preparar la consulta para buscar al usuario por su ID
    $stmt = $conn->prepare("SELECT username, contraseña FROM users WHERE username = :username");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);

    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar la contraseña
        if (password_verify($password, $user['contraseña'])) {
            // Credenciales correctas, establecer variables de sesión
            $_SESSION['username'] = $user['username'];

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
