
<?php
session_start();

require 'includes/conexion.php'; // Verifica que la ruta es correcta
$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

// Crear la cadena de conexión
$dsn = "host=$host port=$port dbname=$database user=$user password=$password";

// Establecer conexión
$conn = pg_connect($dsn);

$mesero = $_REQUEST['mesero'];
$amabilidad_mesero = $_REQUEST['amabilidad_mesero'];
$calificacion_pedido = $_REQUEST['calificacion_pedido'];
$comentario = $_REQUEST['comentario'];
$calificacion_queja = $_REQUEST['calificacion_queja'];
$direccion_queja = $_REQUEST['direccion_queja'];
$queja_comida = $_REQUEST['queja_comida'];

$nombre_encuestado = $_REQUEST['nombre_encuestado'];



echo($mesero);
echo($amabilidad_mesero);
echo($calificacion_pedido);
echo($comentario);
echo($calificacion_queja);
echo($direccion_queja);
echo($queja_comida);
echo($nombre_encuestado);
var_dump($mesero);
var_dump($amabilidad_mesero);
var_dump($calificacion_pedido);
var_dump($comentario);
var_dump($calificacion_queja);
var_dump($direccion_queja);
var_dump($queja_comida);
var_dump($nombre_encuestado);
/*
$insert_encuesta_queja = "INSERT INTO quejas (cliente_nombre, fecha, motivo, puntuacion, plato_nombre, mesero_id) VALUES ('$_REQUEST['nombre_encuestado']', NOW(),'$_REQUEST['comentario']', '$_REQUEST['calificacion_queja']', '$_REQUEST['queja_comida']', '$_REQUEST['amabilidad_mesero']')";
$resultado2 = pg_query($conn,$insert_encuesta_queja); 
/*
if ($resultado2) {
    $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
} else {
    $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
    
}
*/
pg_close();
echo("se enviaron los datos");
/*


$insert_encuesta_meseros = "INSERT INTO encuesta_mesero (mesero_id, puntuacion_amabilidad, puntuacion_exactitud, fecha_encuesta)VALUES ($1, $2, $3, $4)";

$resultado1 = pg_query_params($conn, $insert_encuesta_meseros, array($mesero, $amabilidad_mesero, $calificacion_pedido, Now()));

if ($resultado1) {
    $insert_encuesta_queja = "INSERT INTO quejas (cliente_nombre, fecha, motivo, puntuacion, plato_nombre, mesero_id)VALUES ($1, $2, $3, $4, $5, $6)";
    $resultado2 = pg_query_params($conn, $insert_encuesta_queja, array($nombre_encuestado, NOW(), $comentario, $calificacion_queja, $queja_comida,$mesero ));

    if ($resultado2) {
        $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
    } else {
        $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
    }
} else {
    $_SESSION['user_alert'] = "Error resultado 1: " . pg_last_error($conn);
}
// Cierra la conexión
pg_close($conn);

// Redirecciona si todo fue exitoso
//header('Location: Agregar_Items_Cuenta.php');
//exit();
*/
echo("Fin");
?>