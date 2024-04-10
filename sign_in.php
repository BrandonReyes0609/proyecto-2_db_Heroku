<?php
include 'includes/conexion.php';

// Comprobar que se está haciendo una petición POST y que los campos requeridos están presentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nombre_usuario']) && isset($_POST['password'])) {
    // Recoger los datos del formulario
    $nombre_usuario = trim($_POST['nombre_usuario']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hashear la password

    $stmt = $conn->prepare("INSERT INTO users (nombre_usuario, password) VALUES (:nombre_usuario, :password)");
    $stmt->bindParam(':nombre_usuario', $nombre_usuario, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);

    try {
        $stmt->execute();
        echo "Usuario registrado exitosamente.";
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        // Código de error de PostgreSQL para violaciones de la unicidad
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