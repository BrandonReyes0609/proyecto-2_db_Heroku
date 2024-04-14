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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla Cocina</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    
<nav class="navbar navbar-expand-sm navbar-light bg-light">
    <?php include 'includes/navbar.php'; ?>
</nav>

<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2>Pedidos Pendientes</h2>
            <button onclick="cargarPedidos('false')" class="btn btn-info">Mostrar Platillos</button>
            <button onclick="cargarPedidos('true')" class="btn btn-info">Mostrar Bebidas</button>
            <div id="pedidos-pendientes" class="mt-3">
                <!-- Aquí se mostrarán los pedidos pendientes en orden FIFO -->
            </div>
        </div>
    </div>
</div>

<script>
    // Pasamos el parámetro esBebida como un string para que sea más sencillo manejarlo en el fetch
    function cargarPedidos(esBebida) {
        fetch(`app/cocina/gPedidosPendientes.php?esBebida=${esBebida}`)
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    console.error('Error:', data.error);
                    return;
                }

                const container = document.getElementById('pedidos-pendientes');
                container.innerHTML = ''; // Limpiar contenedor antes de agregar nuevos pedidos

                data.pedidos.forEach(pedido => {
                    const pedidoDiv = document.createElement('div');
                    pedidoDiv.classList.add('card', 'mb-3');
                    pedidoDiv.innerHTML = `
                        <div class="card-body">
                            <p class="card-text">Hora de pedido: ${pedido.fecha_hora}</p>
                            <p class="card-text">Cuenta ID: ${pedido.cuenta_id}</p>
                            <p class="card-text">Plato: ${pedido.plato_nombre}</p>
                            <p class="card-text">Cantidad: ${pedido.cantidad}</p>
                            <button onclick="marcarComoCocinado(${pedido.cuenta_id}, ${pedido.item_id})" class="btn btn-primary">Marcar como Cocinado</button>
                        </div>
                    `;
                    container.appendChild(pedidoDiv);
                });
            })
            .catch(error => console.error('Error al cargar los pedidos:', error));
    }

    // Asegúrate de que esta función ahora maneja bien la respuesta
    function marcarComoCocinado(cuentaId, itemId) {
        fetch('app/cocina/marcar_como_cocinado.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `cuenta_id=${cuentaId}&item_id=${itemId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                cargarPedidos('false'); // Aquí podrías decidir si quieres recargar platillos o bebidas por defecto
            } else if (data.error) {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    // Puedes dejar esta función para que cargue por defecto platillos, bebidas o ambos según prefieras
    document.addEventListener('DOMContentLoaded', function() {
        cargarPedidos('false'); // Por defecto carga platillos
    });

    // Considera remover el setInterval o ajustarlo para que no sobrecargue el servidor
    setInterval(cargarPedidos, 1000);
</script>

</body>
</html>
