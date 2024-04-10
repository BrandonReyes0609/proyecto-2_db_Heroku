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
                <label for="user_id">Usuario:</label>
                <input type="text" id="user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="password" required>
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
                <label for="new_user_id">Usuario:</label>
                <input type="text" id="new_user_id" name="user_id" required>
            </div>
            <div class="form-group">
                <label for="new_password">Contraseña:</label>
                <input type="password" id="new_password" name="password" required>
            </div>
            <div class="form-group">
                <label for="rol">Rol:</label>
                <select id="rol" name="rol" required>
                    <option value="">Seleccionar Rol</option>
                    <option value="mesero">Mesero</option>
                    <option value="cocinero">Cocinero</option>
                    <option value="administrador">Administrador</option>
                    <!-- Agrega más roles según sea necesario -->
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
