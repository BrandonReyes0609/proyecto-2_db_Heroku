<?php
    require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes
    $host = "cb4l59cdg4fg1k.cluster-czrs8kj4isg7.us-east-1.rds.amazonaws.com";
    $database = "dceql5bo9j3plb";
    $user = "u1e25j4kkmlge1";
    $port = "5432";
    $password = "p4ac621d657dad701bc6ed9505ad96894fe1a390fd1e05ef41b37334c60753c5b";

    // Crear la cadena de conexión
    $dsn = "host=$host port=$port dbname=$database user=$user password=$password";

    // Establecer conexión
    $conn = pg_connect($dsn);
    /*echo($_REQUEST['tipo_cuenta']);
    $tipo_cuenta = $_REQUEST['tipo_cuenta'];*/
    // Verificar si 'tipo_cuenta' está presente en la solicitud y es numérico
    if (isset($_REQUEST['tipo_cuenta']) && is_numeric($_REQUEST['tipo_cuenta'])) {
        $tipo_cuenta = $_REQUEST['tipo_cuenta'];
        echo("exito");

        $query_comida = "SELECT items_cuenta.item_id,items_cuenta.cantidad,items_cuenta.fecha_hora,items_cuenta.cocinado,platos.plato_id,platos.nombre,platos.descripcion,platos.precio,platos.tipo,(items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";
        
        $query_cuentas = "SELECT meseros.mesero_id, meseros.nombre_mesero FROM meseros JOIN mesas ON meseros.area_id = mesas.area_id JOIN cuentas ON mesas.mesa_id = cuentas.mesa_id WHERE cuentas.cuenta_id = $tipo_cuenta";
        $query_comidas_nombre = "SELECT platos.plato_id, platos.nombre FROM platos JOIN items_cuenta ON platos.plato_id = items_cuenta.item_id WHERE items_cuenta.cuenta_id = $tipo_cuenta";
        //$resultado1 = pg_query_params($conn, $consulta_comida, array($tipo_cuenta));

        $consulta_meseros_zona = pg_query($conn,$query_cuentas);
        $consulta_comida = pg_query($conn,$query_comida);
        $consulta_comida_nombre = pg_query($conn,$query_comidas_nombre);



    } else {
        // Manejo de error si 'tipo_cuenta' no está presente o no es válido
        echo "Tipo de cuenta no especificado o inválido.";
        $tipo_cuenta = null; // Asegurarse de que no se proceda con un valor inválido
    }

    //cuentas.cuenta_id,cuentas.mesa_id,cuentas.fecha_apertura,cuentas.fecha_cierre,,
    
    
    // Cierra la conexión
    
    // Redirecciona si todo fue exitoso
    //header('Location: Impresion_pedido.php');
    //exit();
?>


  <!DOCTYPE html>
  <html lang="es">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Fomrulario Resenia</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/estilos.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
  </nav>

  <div class="home-container">
      <h1>Fomrulario Resenia</h1>

        <table border="1">
        <thead>
            <tr>
                <th>item_id</th>
            <th>cantidad</th>
            <th>fecha_hora</th>
            <th>cocinado</th>
            <th>plato_id</th>
            <th>nombre</th>
            <th>descripcion</th>
            <th>precio</th>
            <th>tipo</th>
            <th>total_item</th>
            
            </tr>
        </thead>
        <tbody>
        <?php
            while($obj = pg_fetch_object($consulta_comida)){ ?>
                <tr>
                <td><?php echo($obj->item_id);?></td>
                <td><?php echo($obj->cantidad);?></td>
                <td><?php echo($obj->fecha_hora);?></td>
                <td><?php echo($obj->cocinado);?></td>
                <td><?php echo($obj->plato_id);?></td>
                <td><?php echo($obj->nombre);?></td>
                <td><?php echo($obj->descripcion);?></td>
                <td><?php echo($obj->precio);?></td>
                <td><?php echo($obj->tipo);?></td>
                <td><?php echo($obj->total_item);?></td>
                </tr>
            </tbody>
            <?php
            }
            ?>

        </table>


        <form action="envio_formulario.php">

        <span>Ingrese su nombre:</span>
        <input type="text" id="nombre_encuestado" name="nombre_encuestado" size="100"><br><br>


        <span>Seleccione el mesero:</span>
        <select name="mesero" id="mesero">
          <?php
            while($obj = pg_fetch_object($consulta_meseros_zona)){?>
              <option value="<?php echo ($obj->mesero_id) ?>"><?php echo($obj->nombre_mesero);?></option>
            <?php
            
            }
          ?>
        </select>
        <br>
        <label>Califique la amabilidad del mesero</label><br>
        <span>1 bajo y 5 muy alto</span><br>
          <input type="radio" id="1" name="amabilidad_mesero" value="1">
          <span for="1">1</span><br>
          <input type="radio" id="2" name="amabilidad_mesero" value="2">
          <span for="css">2</span><br>
          <input type="radio" id="3" name="amabilidad_mesero" value="3">
          <span for="3">3</span><br>
          <input type="radio" id="4" name="amabilidad_mesero" value="4">
          <span for="css">4</span><br>
          <input type="radio" id="5" name="amabilidad_mesero" value="5">
          <span for="5">5</span>

        <br><br>
        <label>Califique la exactitud de lo recibido respecto a lo solicitado al mesero </label>
        <span>1 bajo y 5 muy alto</span><br>
          <input type="radio" id="1" name="calificacion_pedido" value="1">
          <span for="1">1</span><br>
          <input type="radio" id="2" name="calificacion_pedido" value="2">
          <span for="css">2</span><br>
          <input type="radio" id="3" name="calificacion_pedido" value="3">
          <span for="3">3</span><br>
          <input type="radio" id="4" name="calificacion_pedido" value="4">
          <span for="css">4</span><br>
          <input type="radio" id="5" name="calificacion_pedido" value="5">
          <span for="5">5</span> 
        <br>

        <ingrewse for="fname">Ingrese comentario:</label>
        <input type="text" id="comentario" name="comentario" size="100"><br><br>

        <br><br>
        <label>Califique el nivel de la queja </label>
        <span>1 bajo y 5 muy alto</span><br>
          <input type="radio" id="1" name="calificacion_queja" value="1">
          <span for="1">1</span><br>
          <input type="radio" id="2" name="calificacion_queja" value="2">
          <span for="css">2</span><br>
          <input type="radio" id="3" name="calificacion_queja" value="3">
          <span for="3">3</span><br>
          <input type="radio" id="4" name="calificacion_queja" value="4">
          <span for="css">4</span><br>
          <input type="radio" id="5" name="calificacion_queja" value="5">
          <span for="5">5</span> 
        <br>


        <br>
        <span>Seleccione al destinatario: </span>
        <select name="direccion_queja" id="mesero">
            <option value=""></option>
            <option value="Chef">Chef</option>
            <option value="Host">Host</option>
            <option value="Plato">Plato</option>
            <option value="Bebida">Bebida</option>
        </select>

        <span>Seleccione el plato:</span>
        <select name="queja_comida" id="mesero">
          <?php
            while($obj = pg_fetch_object($consulta_comida_nombre)){?>
              <option value="<?php echo ($obj->plato_id) ?>"><?php echo($obj->nombre);?></option>
            <?php
            
            }
          ?>
        </select>
        <input type="submit" value="Generar reseña">

      </form>



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