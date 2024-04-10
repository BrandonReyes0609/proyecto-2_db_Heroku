<?php
include 'includes/conexion.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_usuario']) && isset($_POST['password']) && isset($_POST['rol'])) {
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $rol = trim($_POST['rol']);

    $stmt = $conn->prepare("INSERT INTO users (nombre_usuario, password, rol) VALUES (:nombre_usuario, :password, :rol)");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

    try {
        $stmt->execute();
        $_SESSION['user_alert'] = "Usuario registrado correctamente!";
        $_SESSION['nombre_usuario'] = $nombre_usuario;
        header("Location: home.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION['error'] = $e->getCode() == 23505 ? "El nombre de usuario ya existe." : "Error al registrar el usuario: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION['error'] = "Por favor complete todos los campos.";
    header("Location: index.php");
    exit;
}
?>
