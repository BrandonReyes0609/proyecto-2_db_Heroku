<?php
include 'includes/conexion.php';

// Comprobar que se está haciendo una petición POST y que los campos requeridos están presentes
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['username']) && isset($_POST['password'])) {
    // Recoger los datos del formulario
    $username = trim($_POST['username']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hashear la contraseña

    $stmt = $conn->prepare("INSERT INTO users (username, contraseña) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
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