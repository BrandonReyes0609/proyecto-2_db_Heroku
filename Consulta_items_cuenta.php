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


if (!$conn) {
    die("Error: No se pudo establecer conexión con la base de datos.");
}

$tipo_cuenta = $_REQUEST['tipo_cuenta'];

// Prepara las consultas SQL para platos y bebidas
$query_platos = "SELECT * FROM items_cuenta WHERE cuenta_id = $1";
//$query_platos = "SELECT cuentas.cuenta_id,cuentas.mesa_id,cuentas.fecha_apertura,cuentas.fecha_cierre,cuentas.total,items_cuenta.item_id,items_cuenta.cantidad,items_cuenta.fecha_hora,items_cuenta.cocinado,platos.plato_id,platos.nombre,platos.descripcion,platos.precio,platos.tipo,(items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $1";




// Preparar y ejecutar consulta SQL
$query_platos = "SELECT * FROM items_cuenta WHERE cuenta_id = $1";
$resultado1 = pg_query_params($conn, $query_platos, array($tipo_cuenta));
if (!$resultado1) {
    pg_close($conn); // Cerrar la conexión antes de salir
    die("Error: La consulta SQL falló. Error: " . pg_last_error($conn));
}
else
{
    //$consulta_pedidos1 = pg_query($conn,$resultado1);
}

// Procesar el resultado
if (pg_num_rows($resultado1) > 0) {
    // Realizar operaciones con los resultados si es necesario
    // Por ejemplo, guardar resultados en un arreglo para usar más tarde
} else {
    echo "Aviso: No se encontraron datos.";
}

// Cierra la conexión
pg_close($conn);

// Redireccionar si todo fue exitoso
//header('Location: Impresion_pedido.php');
//exit();
?>