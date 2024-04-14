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
    </style>
</head>
<body>
    <div class="container">
        <h1 class="mb-4">Reportes del Restaurante</h1>
        <form method="post" class="form-inline mb-4">
            <div class="form-group">
                <label for="fecha_inicio" class="mr-2">Fecha de inicio:</label>
                <input type="date" id="fecha_inicio" name="fecha_inicio" class="form-control mr-4" required>
            </div>
            <div class="form-group">
                <label for="fecha_fin" class="mr-2">Fecha de fin:</label>
                <input type="date" id="fecha_fin" name="fecha_fin" class="form-control mr-4" required>
            </div>
            <button type="submit" name="reporte" value="platos_mas_pedidos" class="btn btn-primary mr-2">Platos Más Pedidos</button>
            <button type="submit" name="reporte" value="horario_mas_pedidos" class="btn btn-primary mr-2">Horario con Más Pedidos</button>
            <button type="submit" name="reporte" value="promedio_tiempo_comida" class="btn btn-primary mr-2">Promedio Tiempo de Comida</button>
            <button type="submit" name="reporte" value="quejas_por_persona" class="btn btn-primary mr-2">Quejas por Persona</button>
            <button type="submit" name="reporte" value="quejas_por_plato" class="btn btn-primary mr-2">Quejas por Plato</button>
            <button type="submit" name="reporte" value="eficiencia_meseros" class="btn btn-primary">Eficiencia de Meseros</button>
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
