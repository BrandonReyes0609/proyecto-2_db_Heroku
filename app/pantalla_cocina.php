<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pantalla Cocina</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div id="pedidos-pendientes">
        <!-- Aquí se mostrarán los pedidos pendientes -->
    </div>

    <script>
        function cargarPedidos() {
            fetch('app/get_pedidos_pendientes.php')
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
            fetch('app/marcar_como_cocinado.php', {
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

        // Inicializar la carga de pedidos y configurar la actualización automática cada 1 segundo
        document.addEventListener('DOMContentLoaded', cargarPedidos);
        setInterval(cargarPedidos, 1000);
    </script>
</body>
</html>
