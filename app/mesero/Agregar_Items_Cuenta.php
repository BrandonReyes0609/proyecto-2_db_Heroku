<?php
session_start(); // Iniciar o continuar la sesión

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

// Almacenar mensaje de alerta en una variable y limpiar la sesión
$userAlert = "";
if (isset($_SESSION['user_alert'])) {
    $userAlert = $_SESSION['user_alert'];
    unset($_SESSION['user_alert']); // Limpiar esa variable de sesión después de usarla
}


$cuentaId = $_POST['tipo_cuenta'];
$platoId = $_POST['tipo_plato'];
$bebidaId = $_POST['tipo_bebida'];
$cantidadPlatos = $_POST['num_platos'];
$cantidadBebidas = $_POST['num_bebida'];

try {
    // Insertar ítem de plato a la cuenta
    $stmt = $conn->prepare("INSERT INTO items_cuenta (cuenta_id, item_id, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$cuentaId, $platoId, $cantidadPlatos]);

    // Insertar ítem de bebida a la cuenta
    $stmt = $conn->prepare("INSERT INTO items_cuenta (cuenta_id, item_id, cantidad) VALUES (?, ?, ?)");
    $stmt->execute([$cuentaId, $bebidaId, $cantidadBebidas]);

    // Establecer mensaje de éxito
    $_SESSION['user_alert'] = "Ítems agregados correctamente a la cuenta.";
} catch (PDOException $e) {
    // Manejar error
    $_SESSION['error'] = "Error al agregar ítems: " . $e->getMessage();
}

header("Location: Agregar_Items_Cuenta.php");


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
  <a class="navbar-brand" href="#">Mi Sitio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <li class="nav-item active">
        <a class="nav-link" href="#">Inicio</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Crear_cuenta.php">Crear Cuenta</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Agregar_Items_Cuenta.php">Items Cuenta</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="Pantalla_cocina.php">Pantalla Cocina</a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="#more">Más</a>
      </li>
    </ul>
  </div>
</nav>

<div class="home-container">
    <h1>Agregar Items</h1>
    <p>Estás autenticado como <?php echo htmlspecialchars($_SESSION['nombre_usuario']); ?></p>
    <p><a href="logout.php">Cerrar Sesión</a></p>

    <form action="#.php">
    <label for="lang">Selecciones la cuenta:</label>
        <select name="tipo_cuenta" id="tipo_cuenta">
            <option value="cuenta1">Cuenta 1</option>
            <option value="cuenta2">Cuenta 2</option>
            <option value="cuenta3">Cuenta 3</option>
        </select>

        <label for="lang">Platos:</label>
        <select name="tipo_plato" id="tipo_plato">
            <option value="plato1">Plato 1</option>
            <option value="plato2">Plato 2</option>
            <option value="plato3">Plato 3</option>
        </select>
        <input type="number" id="num_platos" name="num_platos" min="1" max="100" />

        <label for="lang">Bebidas:</label>
        <select name="tipo_bebida" id="tipo_bebida">
            <option value="bebida1">Bebida 1</option>
            <option value="bebida2">Bebida 2</option>
            <option value="bebida3">bebida 3</option>
        </select>
        <input type="number" id="num_bebida" name="num_bebida" min="1" max="100" />


        <br>
        <input type="submit" value="Confirma Comida">

      <br>
    </form>

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