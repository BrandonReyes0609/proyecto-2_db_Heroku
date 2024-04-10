<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión y Registro</title>
    <link rel="stylesheet" href="css/estilo.css">
</head>
<body>
    <div class="login-container">
        <h2>Iniciar Sesión</h2>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="nombre_usuario">Usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="contraseña">Contraseña:</label>
                <input type="password" id="contraseña" name="contraseña" required>
            </div>
            <div class="form-group">
                <input type="submit" value="Iniciar Sesión">
            </div>
        </form>
    </div>

    <div class="register-container">
        <h2>Registro</h2>
        <form action="sign_in.php" method="POST">
            <div class="form-group">
                <label for="nombre_usuario">Nombre de usuario:</label>
                <input type="text" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="form-group">
                <label for="new_password">Contraseña:</label>
                <input type="password" id="new_contraseña" name="contraseña" required>
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
        </form>
    </div>
    <script src="js/scripts.js"></script>
</body>
</html>
