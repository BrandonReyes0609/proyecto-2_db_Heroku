<?php
session_start();

require 'includes/conexion.php';

// Crear la cadena de conexión directamente
$dsn = "host=cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com port=5432 dbname=dceql5bo9j3plb user=u1e25j4kkmlge1 password=p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Establecer conexión
$conn = pg_connect($dsn);
$tipo_cuenta = $_REQUEST['tipo_cuenta'];
$tipo_plato = $_REQUEST['tipo_plato'];
$num_platos = $_REQUEST['num_platos'];
$tipo_bebida = $_REQUEST['tipo_bbeida'];  // Corregir el typo aquí si fue un error
$num_bebida = $_REQUEST['num_bebida'];

$errores = [];

// Insertar platos solo si se proporciona tipo_plato y num_platos
if (!empty($tipo_plato) && !empty($num_platos)) {
    $query_platos = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
    $resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta, $tipo_plato, $num_platos));
    if (!$resultado1) {
        $errores[] = "Error al insertar plato: " . pg_last_error($conn);
    }
}

// Insertar bebidas solo si se proporciona tipo_bebida y num_bebida
if (!empty($tipo_bebida) && !empty($num_bebida)) {
    $query_bebidas = "INSERT INTO items_cuenta (cuenta_id, item_id, cantidad, fecha_hora, cocinado) VALUES ($1, $2, $3, NOW(), false)";
    $resultado2 = pg_query_params($conn, $query_bebidas, array($tipo_cuenta, $tipo_bebida, $num_bebida));
    if (!$resultado2) {
        $errores[] = "Error al insertar bebida: " . pg_last_error($conn);
    }
}

// Manejar los resultados
if (empty($errores)) {
    $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
} else {
    $_SESSION['user_alert'] = implode("\n", $errores);
}

// Cierra la conexión
pg_close($conn);

// Redirecciona si todo fue exitoso o para mostrar errores
header('Location: Agregar_Items_Cuenta.php');
exit();
?>
