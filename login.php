<?php
include 'includes/conexion.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id']) && isset($_POST['password']) && isset($_POST['rol'])) {
    // Recoger los datos del formulario
    $user_id = trim($_POST['user_id']);
    $password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT); // Hashear la contraseña
    $rol = trim($_POST['rol']);

    // Preparar la consulta para insertar el nuevo usuario
    $stmt = $conn->prepare("INSERT INTO users (user_id, contraseña, rol) VALUES (:user_id, :password, :rol)");
    $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':rol', $rol, PDO::PARAM_STR);

    try {
        $stmt->execute();
        echo "Usuario registrado exitosamente.";
    } catch (PDOException $e) {
        if ($e->getCode() == 23505) { // Código de error de PostgreSQL para violaciones de la unicidad
            echo "El usuario ya existe.";
        } else {
            echo "Error al registrar el usuario: " . $e->getMessage();
        }
    }

    // Cerrar la conexión
    cerrarConexion();
}
?>
