<nav class="navbar navbar-expand-sm navbar-light bg-light">
  <a class="navbar-brand" href="#">Mi Sitio</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
      <!-- Todos los roles ven Inicio -->
      <li class="nav-item active">
        <a class="nav-link" href="home.php">Inicio</a>
      </li>

      <!-- Condicionales segun el rol -->
      <?php if ($_SESSION['rol'] == 'administrador'): ?>
        <!-- Mostrar todo para administrador -->
        <li class="nav-item">
          <a class="nav-link" href="Crear_cuenta.php">Crear Cuenta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Agregar_Items_Cuenta.php">Items Cuenta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Pantalla_cocina.php">Pantalla Cocina</a>
        </li>
        <!-- Agregar otros elementos que solo el administrador puede ver -->
      <?php elseif ($_SESSION['rol'] == 'mesero'): ?>
        <!-- Mostrar para mesero -->
        <li class="nav-item">
          <a class="nav-link" href="Crear_cuenta.php">Crear Cuenta</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="Agregar_Items_Cuenta.php">Items Cuenta</a>
        </li>
      <?php elseif ($_SESSION['rol'] == 'cocinero'): ?>
        <!-- Mostrar para cocinero -->
        <li class="nav-item">
          <a class="nav-link" href="Pantalla_cocina.php">Pantalla Cocina</a>
        </li>
        <!-- Agregar Pantalla de bar -->
      <?php endif; ?>

      <!-- Elementos comunes a varios roles -->
      <li class="nav-item">
        <a class="nav-link" href="../app/login/logout.php">Cerrar sesión</a>
      </li>
    </ul>
  </div>
</nav>
