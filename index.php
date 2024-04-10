<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
</head>
<body>
    hola1
    <h2>Iniciar Sesión</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
        <label for="user_id">Usuario:</label><br>
        <input type="text" id="user_id" name="user_id"><br>
        <label for="password">Contraseña:</label><br>
        <input type="password" id="password" name="password"><br><br>
        <input type="submit" value="Iniciar Sesión">
    </form>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Datos de conexión a la base de datos PostgreSQL
        $host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
        $database = "dceql5bo9j3plb";
        $user = "u1e25j4kkmlge1";
        $port = "5432";
        $password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

        // Recuperar credenciales del formulario
        $user_id = $_POST['user_id'];
        $password = $_POST['password'];

        // Establecer conexión a la base de datos
        try {
            $conn = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Consulta para verificar las credenciales del usuario
            $stmt = $conn->prepare("SELECT * FROM users WHERE user_id = :user_id AND password = :password");
            $stmt->bindParam(':user_id', $user_id);
            $stmt->bindParam(':password', $password);
            $stmt->execute();

            // Verificar si se encontró un usuario con las credenciales proporcionadas
            if($stmt->rowCount() == 1) {
                // Usuario autenticado correctamente
                echo "Inicio de sesión exitoso. Bienvenido, $user_id!";
            } else {
                // Credenciales incorrectas
                echo "Usuario o contraseña incorrectos.";
            }

            // Inserción de datos en la tabla users
            $insert_stmt = $conn->prepare("INSERT INTO users (user_id, password) VALUES (:user_id, :password)");
            $insert_stmt->bindParam(':user_id', $user_id);
            $insert_stmt->bindParam(':password', $password);
            $insert_stmt->execute();

            echo "Datos insertados en la tabla users correctamente.";

        } catch(PDOException $e) {
            // Manejo de errores
            echo "Error de conexión: " . $e->getMessage();
        }
    }
    ?>
</body>
</html>
