<?php
session_start(); // Iniciar o continuar la sesión

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

// Almacenar mensaje de alerta en una variable y limpiar la sesión
if (isset($_SESSION['user_alert'])) {
    $userAlert = $_SESSION['user_alert'];
    unset($_SESSION['user_alert']); // Limpiar esa variable de sesión después de usarla
} else {
    $userAlert = '';
}

if (isset($_POST['Abrir_Cuenta'])) {
    $tipo_area1 = $_POST['tipo_zona']; //tipo de área a asignar
    $num_personas = $_POST['num_personas']; // número de personas
    $unir_mesas = isset($_POST['unir_mesas']) ? 'true' : 'false'; // check de unir mesas
    $numero_mesa = $_POST['numero_mesa']; // número de mesa

    try {
        // Preparar la consulta para insertar la mesa
        $query_mesas = "INSERT INTO mesas (mesa_id, area_id, capacidad, movilidad) VALUES (:numero_mesa, :tipo_area1, :num_personas, :movilidad)";
        $stmt1 = $conn->prepare($query_mesas);
        $stmt1->bindParam(':numero_mesa', $numero_mesa);
        $stmt1->bindParam(':tipo_area1', $tipo_area1);
        $stmt1->bindParam(':num_personas', $num_personas);
        $stmt1->bindParam(':movilidad', $unir_mesas);

        // Ejecutar la consulta para las mesas
        $stmt1->execute();

        // Preparar la consulta para insertar la cuenta
        $query_cuentas = "INSERT INTO cuentas (mesa_id, fecha_apertura) VALUES (:numero_mesa, CURRENT_TIMESTAMP)";
        $stmt2 = $conn->prepare($query_cuentas);
        $stmt2->bindParam(':numero_mesa', $numero_mesa);

        // Ejecutar la consulta para las cuentas
        $stmt2->execute();

        if ($stmt1->rowCount() > 0 && $stmt2->rowCount() > 0) {
            $_SESSION['user_alert'] = "Se enviaron los datos correctamente";
        } else {
            $_SESSION['user_alert'] = "Error al guardar los datos";
        }
    } catch (PDOException $e) {
        $_SESSION['user_alert'] = "Error en la conexión o en las consultas: " . $e->getMessage();
    }
    header("Location: Crear_cuenta.php");
    exit;
}
?>


<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido al Sistema</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>


<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

<div class="home-container">
    <h1>Crear Cuenta</h1>
    <p><?php echo $_POST['tipo_zona']; ?></p>
    <p><?php echo $_POST['unir_mesa']; ?></p>
    <p><?php echo $_POST['num_personas']; ?></p>

    <form action="alta_cuenta.php" method="post">
      <label for="tipo_zona">Ingrese la zona:</label>
      <select name="tipo_zona" id="tipo_zona">
        <!-- Opciones de zona añadidas aquí -->
        <?php
        // Recibir el tipo de área desde un formulario
        $tipo_area = $_POST['tipo_zona'] ?? 1; // Default a 1 si no está definido

        // Preparar la consulta SQL
        $getQueryMeseros = "SELECT DISTINCT * FROM meseros WHERE area_id = :tipo_area";
        $stmt = $conn->prepare($getQueryMeseros);
        $stmt->bindParam(':tipo_area', $tipo_area, PDO::PARAM_INT);

        // Ejecutar la consulta


        // Ejecutar la consulta
        $stmt->execute();

        // Iterar sobre los resultados y generar el HTML para las opciones del dropdown
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre_mesero = $row['mesero_id'];
            echo "<option value='$nombre_mesero'>$nombre_mesero</option>";
        }
        ?>
      </select>

    <h2>Seleccionar mesero mesero</h2>
    <select>
      <?php
        // Reutilizamos el código de consulta anterior para generar otra lista de opciones de meseros
        $stmt->execute(); // Re-ejecutar la misma consulta
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $nombre_mesero = $row['mesero_id'];
            echo "<option value='$nombre_mesero'>$nombre_mesero</option>";
        }
      ?>
    </select>

    
    <p><?php echo $userAlert; ?></p>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="js/scripts.js"></script>
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