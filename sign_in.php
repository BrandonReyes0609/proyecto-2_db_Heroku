<?php
include 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_usuario']) && isset($_POST['password']) && isset($_POST['rol'])) {
    // Recoger los datos del formulario
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hashear la contraseña
    $rol = trim($_POST['rol']); // Recoger el rol del formulario

    // Preparar la consulta para insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO users (nombre_usuario, password, rol) VALUES (:nombre_usuario, :password, :rol)");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

    try {
        $stmt->execute();
        echo "Usuario registrado exitosamente.";
        header("Location: home.php");
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23505) {
            echo "El nombre de usuario ya existe.";
        } else {
            echo "Error al registrar el usuario: " . $e->getMessage();
        }
    }

    // Cerrar la conexión
    cerrarConexion();
} else {
    echo "Por favor complete todos los campos.";
}
?>
