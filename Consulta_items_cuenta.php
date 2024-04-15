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
$tipo_cuenta = $_REQUEST['tipo_cuenta'];

// Prepara las consultas SQL para platos y bebidas
$query_platos = "SELECT * FROM items_cuenta WHERE cuenta_id = '$tipo_cuenta'";
//$query_platos = "SELECT cuentas.cuenta_id,cuentas.mesa_id,cuentas.fecha_apertura,cuentas.fecha_cierre,cuentas.total,items_cuenta.item_id,items_cuenta.cantidad,items_cuenta.fecha_hora,items_cuenta.cocinado,platos.plato_id,platos.nombre,platos.descripcion,platos.precio,platos.tipo,(items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $1";
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta));
$consulta_pedidos1 = pg_query($conn,$resultado1);



// Cierra la conexión
pg_close($conn);

// Redirecciona si todo fue exitoso
header('Location: Impresion_pedido.php');
exit();
?>