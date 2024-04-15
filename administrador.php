<?php
session_start(); // Iniciar o continuar la sesión

require 'includes/conexion.php'; // Incluir el script de conexión desde la carpeta includes

// Verificar si el usuario está autenticado
if (!isset($_SESSION['nombre_usuario'])) {
    header("Location: index.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reportes del Restaurante</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { padding: 20px; }
        .form-group { margin-bottom: 15px; }
        .table { margin-top: 20px; }
        .header { background-color: #007bff; color: white; }
        .wide-btn {
            width: 100%;             /* Makes buttons take the full width of the container */
            margin-top: 10px;
        }
    </style>
</head>
<body>
<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>

    <div class="container">
        <h1 class="mb-4">Reportes del Restaurante</h1>
        <form method="post" class="mb-4">
            <div class="form-row align-items-end"> <!-- Bootstrap class to align items vertically -->
                <div class="col-md-3 mb-3"> <!-- Bootstrap responsive grid -->
                    <label for="fecha_inicio">Fecha de inicio:</label>
                    <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control" required>
                </div>
                <div class="col-md-3 mb-3">
                    <label for="fecha_fin">Fecha de fin:</label>
                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control" required>
                </div>
            </div>
            <button type="submit" name="reporte" value="platos_mas_pedidos" class="btn btn-info wide-btn">Platos Más Pedidos</button>
            <button type="submit" name="reporte" value="horario_mas_pedidos" class="btn btn-success wide-btn">Horario con Más Pedidos</button>
            <button type="submit" name="reporte" value="promedio_tiempo_comida" class="btn btn-warning wide-btn">Promedio Tiempo de Comida</button>
            <button type="submit" name="reporte" value="quejas_por_persona" class="btn btn-danger wide-btn">Quejas por Persona</button>
            <button type="submit" name="reporte" value="quejas_por_plato" class="btn btn-secondary wide-btn">Quejas por Plato</button>
            <button type="submit" name="reporte" value="eficiencia_meseros" class="btn btn-dark wide-btn">Eficiencia de Meseros</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            require 'includes/conexion.php';
            $fecha_inicio = $_POST['fecha_inicio'];
            $fecha_fin = $_POST['fecha_fin'];
            $reporte = $_POST['reporte'];

            switch ($reporte) {
                case "platos_mas_pedidos":
                    platosMasPedidos($fecha_inicio, $fecha_fin, $conn);
            $sql = "SELECT item_id as id, nombre, count(*) as pedidos_totales
                    FROM cuentas c
                    JOIN items_cuenta ic ON ic.cuenta_id = c.cuenta_id
                    JOIN platos ON platos.plato_id = ic.item_id
                    WHERE c.fecha_cierre BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY id, nombre
                    ORDER BY pedidos_totales DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam("ss", $fecha_inicio, $fecha_fin);
            $stmt->execute();
            $result = $stmt->get_result();

            echo "<h2 class='mt-5'>Platos Más Pedidos</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>ID</th><th>Nombre</th><th>Pedidos Totales</th></tr></thead>";
            echo "<tbody>";
            while ($row = $result->fetch_assoc()) {
                echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td><td>{$row['pedidos_totales']}</td></tr>";
            }
            echo "</tbody></table>";
                    break;
                case "horario_mas_pedidos":
                    horarioMasPedidos($fecha_inicio, $fecha_fin, $conn);
                    break;
                case "promedio_tiempo_comida":
                    promedioTiempoComida($fecha_inicio, $fecha_fin, $conn);
                    break;
                case "quejas_por_persona":
                    quejasPorPersona($fecha_inicio, $fecha_fin, $conn);
                    break;
                case "quejas_por_plato":
                    quejasPorPlato($fecha_inicio, $fecha_fin, $conn);
                    break;
                case "eficiencia_meseros":
                    eficienciaMeseros($conn);
                    break;
            }
        }

        function platosMasPedidos($fecha_inicio, $fecha_fin, $pdo) {
            $sql = "SELECT item_id as id, nombre, count(*) as pedidos_totales
                    FROM cuentas c
                    JOIN items_cuenta ic ON ic.cuenta_id = c.cuenta_id
                    JOIN platos ON platos.plato_id = ic.item_id
                    WHERE c.fecha_cierre BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY id, nombre
                    ORDER BY pedidos_totales DESC";
            
            // Preparamos la sentencia con PDO
            $stmt = $pdo->prepare($sql);
            
            // Vinculamos los parámetros
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            
            // Ejecutamos la consulta
            $stmt->execute();
            
            // Comenzamos a construir la tabla HTML para mostrar los resultados
            echo "<h2 class='mt-5'>Platos Más Pedidos</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>ID</th><th>Nombre</th><th>Pedidos Totales</th></tr></thead>";
            echo "<tbody>";
            
            // Recorremos los resultados y los imprimimos
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['id']}</td><td>{$row['nombre']}</td><td>{$row['pedidos_totales']}</td></tr>";
            }
            
            echo "</tbody></table>";
        }
        
        function horarioMasPedidos($fecha_inicio, $fecha_fin, $pdo) {
            $sql = "SELECT EXTRACT(HOUR FROM fecha_hora) AS hora, COUNT(*) AS total_pedidos
                    FROM items_cuenta
                    WHERE fecha_hora BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY hora
                    ORDER BY total_pedidos DESC
                    LIMIT 1";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
        
            echo "<h2 class='mt-5'>Horario con Más Pedidos</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>Hora</th><th>Total Pedidos</th></tr></thead>";
            echo "<tbody>";
            if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['hora']}</td><td>{$row['total_pedidos']}</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        function promedioTiempoComida($fecha_inicio, $fecha_fin, $pdo) {
            $sql = "SELECT cantidad_personas, 
                           ROUND(AVG(EXTRACT(EPOCH FROM duracion)/60)) AS promedio_tiempo_minutos
                    FROM (
                        SELECT cuenta_id,
                               COUNT(*) AS cantidad_personas,
                               MAX(fecha_cierre - fecha_apertura) AS duracion
                        FROM cuentas
                        WHERE fecha_apertura BETWEEN :fecha_inicio AND :fecha_fin
                        GROUP BY cuenta_id
                    ) AS comidas_por_cuenta
                    GROUP BY cantidad_personas
                    ORDER BY cantidad_personas";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
        
            echo "<h2 class='mt-5'>Promedio de Tiempo de Comida por Cantidad de Personas</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>Cantidad de Personas</th><th>Promedio de Tiempo (Minutos)</th></tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['cantidad_personas']}</td><td>{$row['promedio_tiempo_minutos']}</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        function quejasPorPersona($fecha_inicio, $fecha_fin, $pdo) {
            $sql = "SELECT m.nombre_mesero AS nombre_mesero, COUNT(*) AS total_quejas
                    FROM quejas q
                    JOIN meseros m ON q.mesero_id = m.mesero_id
                    WHERE q.fecha BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY m.nombre_mesero
                    ORDER BY total_quejas DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
        
            echo "<h2 class='mt-5'>Quejas por Persona</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>Mesero</th><th>Total de Quejas</th></tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['nombre_mesero']}</td><td>{$row['total_quejas']}</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        function quejasPorPlato($fecha_inicio, $fecha_fin, $pdo) {
            $sql = "SELECT pl.nombre AS nombre_plato, COUNT(*) AS total_quejas
                    FROM quejas q
                    JOIN platos pl ON q.plato_id = pl.plato_id
                    WHERE q.fecha BETWEEN :fecha_inicio AND :fecha_fin
                    GROUP BY pl.nombre
                    ORDER BY total_quejas DESC";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':fecha_inicio', $fecha_inicio);
            $stmt->bindParam(':fecha_fin', $fecha_fin);
            $stmt->execute();
        
            echo "<h2 class='mt-5'>Quejas por Plato</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>Plato</th><th>Total de Quejas</th></tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['nombre_plato']}</td><td>{$row['total_quejas']}</td></tr>";
            }
            echo "</tbody></table>";
        }
        
        function eficienciaMeseros($pdo) {
            $sql = "SELECT m.nombre_mesero AS nombre_mesero,
                           EXTRACT(MONTH FROM em.fecha_encuesta) AS mes,
                           COUNT(*) AS total_encuestas,
                           AVG(em.puntuacion_amabilidad) AS promedio_amabilidad,
                           AVG(em.puntuacion_exactitud) AS promedio_exactitud
                    FROM encuesta_mesero em
                    JOIN meseros m ON em.mesero_id = m.mesero_id
                    WHERE em.fecha_encuesta >= CURRENT_DATE - INTERVAL '6 months'
                    GROUP BY m.nombre_mesero, EXTRACT(MONTH FROM em.fecha_encuesta)
                    ORDER BY nombre_mesero, mes";
            $stmt = $pdo->prepare($sql);
            $stmt->execute();
        
            echo "<h2 class='mt-5'>Eficiencia de Meseros (Últimos 6 Meses)</h2>";
            echo "<table class='table table-striped'>";
            echo "<thead class='header'><tr><th>Mesero</th><th>Mes</th><th>Total Encuestas</th><th>Promedio Amabilidad</th><th>Promedio Exactitud</th></tr></thead>";
            echo "<tbody>";
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr><td>{$row['nombre_mesero']}</td><td>{$row['mes']}</td><td>{$row['total_encuestas']}</td><td>".round($row['promedio_amabilidad'], 2)."</td><td>".round($row['promedio_exactitud'], 2)."</td></tr>";
            }
            echo "</tbody></table>";
        }
    ?>        
    </div>
</body>
</html>
