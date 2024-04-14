<?php
include 'includes/conexion.php';
session_start();

// Obtiene la ruta de la URL
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$action = $uri[1] ?? null; // Asumiendo que la acción está en el primer segmento de la ruta

switch ($action) {
    case 'login':
        require 'login.php';
        break;
    case 'logout':
        require 'logout.php';
        break;
    case 'get_pedidos_pendientes':
        require 'get_pedidos_pendientes.php';
        break;
    case 'marcar_como_cocinado':
        // Solo permitir este caso si el método es POST
        if ($method == 'POST') {
            require 'marcar_como_cocinado.php';
        }
        break;
    case 'agregar_item':
        // Solo permitir este caso si el método es POST
        if ($method == 'POST') {
            require 'agregar_item_a_cuenta.php';
        }
        break;
    default:
        echo json_encode(['error' => 'Acción no reconocida']);
        break;
}