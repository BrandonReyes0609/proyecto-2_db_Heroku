<?php
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Crear la cadena de conexión
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";

// Establecer conexión
$conn = pg_connect($dsn);

$tipo_area1 = $_REQUEST['tipo_zona'];
$num_personas = $_REQUEST['num_personas'];
$numero_mesa = $_REQUEST['numero_mesa'];

$query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES ($1, $2, $3, false)";
$resultado1 = pg_query_params($conn, $query_mesas, array($numero_mesa, $tipo_area1, $num_personas));

if ($resultado1) {
    $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES ($1, NOW(), null, 0)";
    $resultado2 = pg_query_params($conn, $query_cuentas, array($numero_mesa));

    if ($resultado2) {
        echo "<h3>Se enviaron los datos correctamente</h3>";
    } else {
        echo "<h3>Error resultado 2: " . pg_last_error($conn) . "</h3>";
    }
} else {
    echo "<h3>Error resultado 1: " . pg_last_error($conn) . "</h3>";
}

pg_close($conn);
header('Location: Crear_cuenta.php');
exit(); // No olvides llamar a exit() después de header()
?>

