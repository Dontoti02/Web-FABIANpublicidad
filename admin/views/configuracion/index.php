<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="<?php echo BASE_URL; ?>admin/assets/admin-style.css" rel="stylesheet">
</head>
<body>
    <div class="sidebar p-3">
        <!-- Logo Section -->
        <div class="text-center mb-4">
            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="FABIAN Logo" class="logo-admin mb-2">
            <h6 class="text-white mb-0 fw-bold">FABIAN</h6>
            <small class="text-white-50">Publicidad</small>
        </div>

        <hr class="text-white-50">

        <div class="d-flex align-items-center mb-4">
            <i class="fas fa-user-shield text-white me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="text-white mb-0"><?php echo $_SESSION['nombre']; ?></h6>
                <small class="text-white-50"><?php echo ucfirst($_SESSION['tipo']); ?></small>
            </div>
        </div>

        <hr class="text-white-50">

        <h5 class="text-white mb-3">
            <i class="fas fa-cogs me-2"></i>Panel de Control
        </h5>

        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (basename($_SERVER['REQUEST_URI']) == 'admin' || strpos($_SERVER['REQUEST_URI'], 'dashboard') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'categorias') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/categorias">
                    <i class="fas fa-th-large me-2"></i>Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'productos') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/productos">
                    <i class="fas fa-box me-2"></i>Productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'usuarios') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/usuarios">
                    <i class="fas fa-users me-2"></i>Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'pedidos') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/pedidos">
                    <i class="fas fa-shopping-cart me-2"></i>Pedidos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo (strpos($_SERVER['REQUEST_URI'], 'configuracion') !== false) ? 'active' : ''; ?>" href="<?php echo BASE_URL; ?>admin/configuracion">
                    <i class="fas fa-cog me-2"></i>Configuración
                </a>
            </li>
        </ul>

        <div class="position-absolute bottom-0 start-0 w-100 p-3">
            <hr class="text-white-50">
            <a href="<?php echo BASE_URL; ?>admin/login/logout" class="btn btn-outline-light w-100">
                <i class="fas fa-sign-out-alt me-2"></i>Cerrar Sesión
            </a>
        </div>
    </div>

    <div class="content p-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="mb-0"><?php echo $title; ?></h1>
                <p class="text-muted mb-0">Gestiona tu información personal y seguridad</p>
            </div>
        </div>

        <div class="row">
            <!-- Datos Personales -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>Datos Personales
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="datosPersonalesForm">
                            <div class="mb-3">
                                <label for="nombre" class="form-label">Nombre</label>
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Actualizar Datos
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Cambio de Contraseña -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-key me-2"></i>Cambiar Contraseña
                        </h5>
                    </div>
                    <div class="card-body">
                        <form id="cambiarContrasenaForm">
                            <div class="mb-3">
                                <label for="contrasena_actual" class="form-label">Contraseña Actual</label>
                                <input type="password" class="form-control" id="contrasena_actual" name="contrasena_actual" required>
                            </div>
                            <div class="mb-3">
                                <label for="contrasena_nueva" class="form-label">Nueva Contraseña</label>
                                <input type="password" class="form-control" id="contrasena_nueva" name="contrasena_nueva" required minlength="6">
                                <div class="form-text">Mínimo 6 caracteres</div>
                            </div>
                            <div class="mb-3">
                                <label for="confirmar_contrasena" class="form-label">Confirmar Nueva Contraseña</label>
                                <input type="password" class="form-control" id="confirmar_contrasena" name="confirmar_contrasena" required minlength="6">
                            </div>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-key me-2"></i>Cambiar Contraseña
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            cargarDatosUsuario();

            // Formulario de datos personales
            $('#datosPersonalesForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/configuracion/actualizarDatosPersonales',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            showAlert(response.msg, 'success');
                            // Actualizar el nombre en el sidebar
                            $('.sidebar .text-white.mb-0').text($('#nombre').val());
                        } else {
                            showAlert(response.msg, 'danger');
                        }
                    },
                    error: function() {
                        showAlert('Error de conexión', 'danger');
                    }
                });
            });

            // Formulario de cambio de contraseña
            $('#cambiarContrasenaForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: '<?php echo BASE_URL; ?>admin/configuracion/cambiarContrasena',
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    success: function(response) {
                        if (response.type === 'success') {
                            $('#cambiarContrasenaForm')[0].reset();
                            showAlert(response.msg, 'success');
                        } else {
                            showAlert(response.msg, 'danger');
                        }
                    },
                    error: function() {
                        showAlert('Error de conexión', 'danger');
                    }
                });
            });
        });

        function cargarDatosUsuario() {
            $.ajax({
                url: '<?php echo BASE_URL; ?>admin/configuracion/obtenerDatosUsuario',
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    console.log('Datos recibidos:', data);
                    if (data && data.nombre && data.email) {
                        $('#nombre').val(data.nombre);
                        $('#email').val(data.email);
                    } else if (data && data.error) {
                        console.error('Error del servidor:', data.error);
                        showAlert('Error del servidor: ' + data.error, 'danger');
                    } else {
                        console.error('Datos del usuario inválidos o vacíos:', data);
                        showAlert('Error: No se pudieron cargar los datos del usuario. Los datos recibidos son inválidos.', 'danger');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error AJAX:', xhr, status, error);
                    console.error('Respuesta del servidor:', xhr.responseText);
                    showAlert('Error al cargar datos del usuario: ' + error, 'danger');
                }
            });
        }

        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            `;
            $('.content').prepend(alertHtml);

            setTimeout(function() {
                $('.alert').alert('close');
            }, 5000);
        }
    </script>
</body>
</html>
