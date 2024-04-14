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
    $query="SELECT * FROM platos WHERE tipo=true";
    $consulta_platos = pg_query($conn,$query);
    
?>