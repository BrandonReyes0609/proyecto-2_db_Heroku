<?php
    // Datos de conexión a la base de datos PostgreSQL
    $host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
    $database = "dceql5bo9j3plb";
    $user = "u1e25j4kkmlge1";
    $port = "5432";
    $password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";
    

    $conn = pg_connect("host=$host port=$port dbname=$database user=$user password=$password");



    //if(isset($_REQUEST['Abrir_Cuenta'])){
    $tipo_area1 = $_REQUEST['tipo_zona'];//tipo de area a asignar
    $num_personas = $_REQUEST['num_personas'];//  nuero de personas
    $unir_mesas = $_REQUEST['unir_mesas'];// chek de unir mesas

    $query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES ('$numero_mesa ', $tipo_area1, $num_personas, NULL)";
    //false son las mesas que se puede mover
    $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura, fecha_cierre, total) VALUES ($numero_mesa, NOW(),null,null)";
    $resultado1 = mysql_query($conn, $query_mesas);
    if($resultado1){
        $resultado2 = mysql_query($conn, $query_cuentas);
        if($resultado2){
        ?>
            <h3>Se enviaron los datos correctamente</h3>
        <?php
        }
        else
        {
        ?>
            <h3>Error resultado 2</h3>
        <?php
        }
    }
    else
    {
        ?>
            <h3>Error resultado 1</h3>
        <?php
    } 
    pg_close();
    //}
?>