<?php
    //Se cierra la cuenta y se consulta las comidas y bebidas, muestra el total
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

    if (isset($_REQUEST['tipo_cuenta']) && is_numeric($_REQUEST['tipo_cuenta'])) {
        $tipo_cuenta = $_REQUEST['tipo_cuenta'];
        echo("exito");

        $query_cuentas = "SELECT cuentas.cuenta_id,cuentas.mesa_id,cuentas.fecha_apertura,cuentas.fecha_cierre,cuentas.total,items_cuenta.item_id,items_cuenta.cantidad,items_cuenta.fecha_hora,items_cuenta.cocinado,platos.plato_id,platos.nombre,platos.descripcion,platos.precio,platos.tipo,(items_cuenta.cantidad * platos.precio) AS total_item FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta";
        $query_total_cuenta = "SELECT cuentas.cuenta_id, SUM(items_cuenta.cantidad * platos.precio) AS sumatoria_total_items FROM cuentas INNER JOIN items_cuenta ON cuentas.cuenta_id = items_cuenta.cuenta_id INNER JOIN platos ON items_cuenta.item_id = platos.plato_id WHERE cuentas.cuenta_id = $tipo_cuenta GROUP BY cuentas.cuenta_id";
        $query_cerrar_cuenta = "UPDATE cuentas SET fecha_cierre = NOW() WHERE cuenta_id = $1";
        //$resultado1 = pg_query_params($conn, $query_cuentas, array($tipo_cuenta));
        $consulta_pedidos1 = pg_query($conn,$query_cuentas);
        $consulta_pedidos2 = pg_query($conn,$query_total_cuenta);

        $resultado_query_cerrar_cuenta  = pg_query_params($conn, $query_cerrar_cuenta, array($tipo_cuenta));
        if ($resultado_query_cerrar_cuenta) {
            $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
        } else {
            $_SESSION['user_alert'] = "Error resultado 2: " . pg_last_error($conn);
        }
        
    } else {
        // Manejo de error si 'tipo_cuenta' no está presente o no es válido
        echo "Tipo de cuenta no especificado o inválido.";
        $tipo_cuenta = null; // Asegurarse de que no se proceda con un valor inválido
    }

    
    
    
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
      <title>Impresion Pedidos</title>
      <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
      <link rel="stylesheet" href="assets/css/estilos.css">
  </head>
  <body>

  <nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
  </nav>

  <div class="home-container">
      <h1>Impresion Pedidos</h1>

      <table>
        <thead>
          <tr>
          <th>cuenta_idmesa_id</th>
            <th>fecha_apertura</th>
            <th>fecha_cierre</th>
            <th>total</th>
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
            while($obj = pg_fetch_object($consulta_pedidos1)){ ?>
              <tr>
                <td><?php echo($obj->cuenta_idmesa_id);?></td>
                <td><?php echo($obj->fecha_apertura);?></td>
                <td><?php echo($obj->fecha_cierre);?></td>
                <td><?php echo($obj->total);?></td>
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
  
      <table>
        <thead>
          <tr>
          <th>Total:</th>    
          </tr>
        </thead>
        <tbody>
        <?php
            while($obj = pg_fetch_object($consulta_pedidos2)){ ?>
              <tr>
                <td><?php echo($obj->sumatoria_total_items);?></td>
              </tr>
            </tbody>
          <?php
            }
          ?>

      </table>
  

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