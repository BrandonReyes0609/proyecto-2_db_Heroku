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
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    
<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <?php include 'includes/navbar.php'; ?>
</nav>
    <div id="pedidos-pendientes">
        <!-- Aquí se mostrarán los pedidos pendientes en orden FIFO -->
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
                    pedidoDiv.classList.add('pedido');
                    pedidoDiv.innerHTML = `
                        <p>Hora de pedido: ${pedido.hora_pedido}</p>
                        <p>Cuenta ID: ${pedido.cuenta_id}</p>
                        <p>Plato: ${pedido.plato_nombre}</p>
                        <p>Cantidad: ${pedido.cantidad}</p>
                        <button onclick="marcarComoCocinado(${pedido.cuenta_id}, ${pedido.item_id})">Marcar como Cocinado</button>
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
