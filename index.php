
DOCTYPE html>
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
            <label for="contraseña">Contraseña:</label><br>
            <input type="contraseña" id="contraseña" name="contraseña"><br><br>
            <input type="submit" value="Iniciar Sesión">
        </form>
        
    <?php
    try {
        // Datos de conexión
        $host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
        $database = "dceql5bo9j3plb";
        $username = "u1e25j4kkmlge1";
        $password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";
        $port = 5432;
    
        // Cadena de conexión
        $dsn = "pgsql:host=$host;port=$port;dbname=$database;user=$username;password=$password";
    
        // Conexión
        $pdo = new PDO($dsn);
    
        // Configurar PDO para que lance excepciones en caso de error
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
        // Mensaje de conexión exitosa
        echo "Conexión exitosa a la base de datos.";
    } catch(PDOException $e) {
        // Mensaje de conexión fallida
        echo "Error en la conexión a la base de datos: " . $e->getMessage();
    }
    ?>
    </body>
</html>
