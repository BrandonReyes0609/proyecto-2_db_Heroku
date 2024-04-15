
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

$mesero = (int) ($_REQUEST['mesero']);
$amabilidad_mesero = $_REQUEST['amabilidad_mesero'];
$calificacion_pedido = (int) ($_REQUEST['calificacion_pedido']);
$comentario = $_REQUEST['comentario'];
$calificacion_queja = (int) ($_REQUEST['calificacion_queja']);
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
$insert_encuesta_queja = "INSERT INTO quejas (cliente_nombre, fecha, motivo, puntuacion, plato_nombre, mesero_id) VALUES ($1, NOW(), $2, $3, $4, $5)";
$params = array($nombre_encuestado, $comentario, $calificacion_queja, $queja_comida, $mesero);

$resultado2 = pg_query_params($conn, $insert_encuesta_queja, $params);

if ($resultado2 === false) {
    echo "Error en la consulta 2: " . pg_last_error($conn);
} else {
    echo "Registro insertado correctamente 2.";
    $insert_encuesta_meseros = "INSERT INTO encuesta_mesero (mesero_id, puntuacion_amabilidad, puntuacion_exactitud, fecha_encuesta)VALUES ($1, $2, $3, NOW())";
    $params2 = array($mesero, $amabilidad_mesero, $calificacion_pedido);
    $resultado1 = pg_query_params($conn, $insert_encuesta_meseros, $params2);
    if ($resultado1 === false) {
        echo "Error en la consulta 1: " . pg_last_error($conn);
    } else {
        echo "Registro insertado correctamente 1.";
    }

}


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

<!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Agregar Items</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/estilos.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
  </nav>

  <div class="home-container">
      <h3>Se envio la comentario/queja</h3>
      <label>Siempre intentamos proporcionar el mejor servicio</label>

  </div>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <script>
  // JavaScript para mostrar la alerta
  window.onload = function() {
      var alertMessage = "<?php echo $userAlert; ?>";
      if (alertMessage) {
          alert(alertMessage);
      }
  };
  </script>
  </body>
  </html>