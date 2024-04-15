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
    
    echo($_POST['tipo_cuenta']);
    echo($_POST['tipo_cuenta']);
    echo($_POST['tipo_cuenta']);
    echo($_POST['tipo_cuenta']);
    echo($_POST['tipo_cuenta']);
    $cuenta_id = $_POST['tipo_cuenta'];
    echo($cuenta_id);
    $nombre_cliente = $_POST['nombre_cliente'];
    $nit_cliente = $_POST['nit_cliente'];
    $direccion_cliente = $_POST['direccion_cliente'];
    $metodo_pago = $_POST['metodo_pago'];
    // Preparar la consulta SQL para insertar los datos del cliente en la tabla cliente
    $sql_insert_cliente = "INSERT INTO cliente (cuenta_id,nombre, nit, direccion) VALUES ($1, $2, $3, $4)";
    //$insert_encuesta_meseros = "INSERT INTO encuesta_mesero (mesero_id, puntuacion_amabilidad, puntuacion_exactitud, fecha_encuesta)VALUES ($1, $2, $3, NOW())";
    $params1 = array($cuenta_id,$nombre_cliente, $nit_cliente, $direccion_cliente);
    $resultado1 = pg_query_params($conn, $sql_insert_cliente, $params1);

    // Ejecutar la consulta
    if ($resultado1 === false) {
        echo "Error en la consulta 1: " . pg_last_error($conn);
    } else {
        echo "Registro insertado correctamente 1.";

    $sql_insert_cliente = "INSERT INTO cliente (cuenta_id,metodo, null) VALUES ($1, $2, $3)";
    $params2 = array($cuenta_id, $metodo);
    $resultado1 = pg_query_params($conn, $sql_insert_cliente, $params2);

    if ($resultado1 === false) {
        echo "Error en la consulta 1: " . pg_last_error($conn);
    } else {
        echo "Registro insertado correctamente 1.";
    }
    }
   
?>