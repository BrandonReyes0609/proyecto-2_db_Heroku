<?php
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";
$conn = pg_connect($dsn);

if (!$conn) {
    echo "<h3>No se pudo conectar a la base de datos</h3>";
    exit;
}

$tipo_area1 = $_REQUEST['tipo_zona'];
$num_personas = $_REQUEST['num_personas'];
$numero_mesa = $_REQUEST['numero_mesa'];

$query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES ($1, $2, $3, false)";
$resultado1 = pg_query_params($conn, $query_mesas, array($numero_mesa, $tipo_area1, $num_personas));

if ($resultado1) {
    $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES ($1, NOW(), NULL, NULL)";
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
?>
