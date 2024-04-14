<?php
include 'includes/conexion.php';

session_start(); // Iniciar la sesión

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_usuario']) && isset($_POST['password'])) {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = trim($_POST['password']);

    $stmt = $conn->prepare("SELECT nombre_usuario, password FROM users WHERE nombre_usuario = :nombre_usuario");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (password_verify($password, $user['password'])) {
            $_SESSION['nombre_usuario'] = $nombre_usuario;  // Guardar el nombre de usuario en la sesión
            header("Location: home.php");
            exit;
        } else {
            $_SESSION['error'] = "Usuario o contraseña incorrectos.";  // Guardar el mensaje de error en la sesión
            header("Location: index.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Usuario o contraseña incorrectos.";
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Por favor complete todos los campos.";
    header("Location: index.php");
    exit;
}
?>
