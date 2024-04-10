<?php
// Datos de conexión a la base de datos PostgreSQL
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

try {
    // Crear una instancia de PDO y establecer una conexión
    $conn = new PDO("pgsql:host=$host;port=$port;dbname=$database;user=$user;password=$password");
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Manejo de errores
    die("Error de conexión: " . $e->getMessage());
}

// Función para cerrar la conexión
function cerrarConexion() {
    global $conn;
    $conn = null;
}
?>
