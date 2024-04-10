<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión y Registro</title>
    <link rel="stylesheet" href="assets/css/estilos.css">
</head>
<body>
    <div class="login-container" id="login-form">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="nombre_usuario">Usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Iniciar Sesión">
            </div>
            <div class="form-group">
                <p>No tienes una cuenta? <a href="#" onclick="mostrarRegistro()">Regístrate aquí</a></p>
            </div>
        </form>
    </div>

    <div class="register-container" id="register-form" style="display:none;">
        <h2>Registro</h2>
        <form action="sign_in.php" method="POST">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input type="text" id="nombre_usuario_reg" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="new_password">Contraseña:</label>
                <input type="password" id="new_password_reg" name="password" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccionar Rol</option>
                    <option value="mesero">Mesero</option>
                    <option value="cocinero">Cocinero</option>
                    <option value="administrador">Administrador</option>
                </select>
            </div>
            <div class="form-group">
                <input type="submit" value="Registrarse">
            </div>
            <div class="form-group">
                <p>¿Ya tienes cuenta? <a href="#" onclick="mostrarRegistro()">Iniciar sesión aquí</a></p>
            </div>
        </form>
    </div>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger">
            <?php 
                echo $_SESSION['error'];
                unset($_SESSION['error']);
            ?>
        </div>
    <?php endif; ?>

    <script src="assets/js/scripts.js"></script>
</body>
</html>
