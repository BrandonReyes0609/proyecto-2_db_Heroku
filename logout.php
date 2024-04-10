<?php
session_start(); // Iniciar o continuar la sesión
session_destroy(); // Destruir todas las variables de sesión
header("Location: index.php"); // Redirigir al inicio de sesión
exit;
?>
