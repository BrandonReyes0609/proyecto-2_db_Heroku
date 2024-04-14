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
            <div id="pedidos-pendientes" class="mt-3">
                <!-- Aquí se mostrarán los pedidos pendientes en orden FIFO -->
            </div>
        </div>
    </div>
</div>

<script>
    function cargarPedidos() {
        fetch('app/cocina/gPedidosPendientes.php')
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
                            <p class="card-text">Hora de pedido: ${pedido.hora_pedido}</p>
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
                cargarPedidos(); // Recargar la lista de pedidos
            } else if (data.error) {
                console.error('Error:', data.error);
            }
        })
        .catch(error => console.error('Error:', error));
    }

    document.addEventListener('DOMContentLoaded', cargarPedidos);
    setInterval(cargarPedidos, 1000);
</script>

</body>
</html>
