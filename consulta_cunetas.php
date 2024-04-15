<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


// Archivo: consulta_cuentas.php

$host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
$database = "dceql5bo9j3plb";
$user = "u1e25j4kkmlge1";
$port = "5432";
$password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

$dsn = "host=$host port=$port dbname=$database user=$user password=$password";
$conn = pg_connect($dsn);

if (!$conn) {
    echo "An error occurred.\n";
    exit;
}

$query_cuentas_abiertas = "SELECT cuenta_id FROM cuentas WHERE fecha_cierre IS NULL";
?>
