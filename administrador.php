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
                    WHERE c.fecha_cierre BETWEEN ? AND ?
                    GROUP BY id, nombre
                    ORDER BY pedidos_totales DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
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

        function platosMasPedidos($fecha_inicio, $fecha_fin, $conn) {
            $sql = "SELECT item_id as id, nombre, count(*) as pedidos_totales
                    FROM cuentas c
                    JOIN items_cuenta ic ON ic.cuenta_id = c.cuenta_id
                    JOIN platos ON platos.plato_id = ic.item_id
                    WHERE c.fecha_cierre BETWEEN ? AND ?
                    GROUP BY id, nombre
                    ORDER BY pedidos_totales DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $fecha_inicio, $fecha_fin);
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
        }

        function horarioMasPedidos($fecha_inicio, $fecha_fin, $conn) {
            
        }

        function promedioTiempoComida($fecha_inicio, $fecha_fin, $conn) {

        }

        function quejasPorPersona($fecha_inicio, $fecha_fin, $conn) {

        }

        function quejasPorPlato($fecha_inicio, $fecha_fin, $conn) {

        }

        function eficienciaMeseros($conn) {

        }
        ?>
    </div>
</body>
</html>
