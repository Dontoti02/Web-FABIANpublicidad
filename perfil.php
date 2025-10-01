<?php
session_start();
include_once("conexionSQL/conexion.php");

// Check if user is logged in
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php');
    exit();
}

$error = '';
$success = '';

// Get user data
$user_id = $_SESSION['cliente_id'];
$stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_profile':
                $nombre = trim($_POST['nombre']);
                $apellidos = trim($_POST['apellidos']);
                $email = trim($_POST['email']);
                $telefono = trim($_POST['telefono']);
                $direccion = trim($_POST['direccion']);
                
                if (empty($nombre) || empty($apellidos) || empty($email)) {
                    $error = 'Los campos nombre, apellidos y email son obligatorios';
                } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $error = 'El email no es válido';
                } else {
                    // Check if email exists for other users
                    $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE email = ? AND id_cliente != ?");
                    $stmt->bind_param("si", $email, $user_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($result->num_rows > 0) {
                        $error = 'Este email ya está registrado por otro usuario';
                    } else {
                        $stmt = $conexion->prepare("UPDATE clientes SET nombre = ?, apellidos = ?, email = ?, telefono = ?, direccion = ? WHERE id_cliente = ?");
                        $stmt->bind_param("sssssi", $nombre, $apellidos, $email, $telefono, $direccion, $user_id);
                        
                        if ($stmt->execute()) {
                            $success = 'Perfil actualizado correctamente';
                            $user['nombre'] = $nombre;
                            $user['apellidos'] = $apellidos;
                            $user['email'] = $email;
                            $user['telefono'] = $telefono;
                            $user['direccion'] = $direccion;
                            $_SESSION['cliente_nombre'] = $nombre;
                        } else {
                            $error = 'Error al actualizar el perfil';
                        }
                    }
                }
                break;
                
            case 'change_password':
                $current_password = $_POST['current_password'];
                $new_password = $_POST['new_password'];
                $confirm_password = $_POST['confirm_password'];
                
                if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
                    $error = 'Todos los campos de contraseña son obligatorios';
                } elseif ($new_password !== $confirm_password) {
                    $error = 'Las nuevas contraseñas no coinciden';
                } elseif (strlen($new_password) < 6) {
                    $error = 'La nueva contraseña debe tener al menos 6 caracteres';
                } elseif (!password_verify($current_password, $user['password'])) {
                    $error = 'La contraseña actual es incorrecta';
                } else {
                    $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
                    $stmt = $conexion->prepare("UPDATE clientes SET password = ? WHERE id_cliente = ?");
                    $stmt->bind_param("si", $hashed_password, $user_id);
                    
                    if ($stmt->execute()) {
                        $success = 'Contraseña actualizada correctamente';
                    } else {
                        $error = 'Error al actualizar la contraseña';
                    }
                }
                break;
        }
    }
}

// Get user's recent orders
$stmt = $conexion->prepare("SELECT v.*, COUNT(dv.id_detalle_venta) as total_items 
                           FROM venta v 
                           LEFT JOIN detalle_venta dv ON v.id_venta = dv.id_venta 
                           WHERE v.id_cliente = ? 
                           GROUP BY v.id_venta 
                           ORDER BY v.fecha DESC 
                           LIMIT 5");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$recent_orders = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Perfil | FABIAN Publicidad</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            color: #1e293b;
            line-height: 1.6;
        }

        /* Modern Navigation */
        .navbar {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(37, 99, 235, 0.1);
            padding: 15px 0;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1000;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        }

        .navbar-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            color: #1e293b;
        }

        .navbar-brand img {
            height: 40px;
            width: auto;
        }

        .brand-text {
            font-size: 1.5rem;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 30px;
            list-style: none;
        }

        .nav-link {
            text-decoration: none;
            color: #64748b;
            font-weight: 500;
            transition: all 0.3s ease;
            padding: 8px 16px;
            border-radius: 8px;
        }

        .nav-link:hover, .nav-link.active {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.1);
            text-decoration: none;
        }

        /* Main Content */
        .main-content {
            margin-top: 90px;
            padding: 40px 0;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .page-header {
            text-align: center;
            margin-bottom: 50px;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
            position: relative;
        }

        .page-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 80px;
            height: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 2px;
        }

        .page-subtitle {
            color: #64748b;
            font-size: 1.2rem;
        }

        /* Profile Layout */
        .profile-layout {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 40px;
            margin-bottom: 40px;
        }

        /* Profile Sidebar */
        .profile-sidebar {
            background: white;
            border-radius: 16px;
            padding: 30px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            height: fit-content;
        }

        .profile-avatar {
            text-align: center;
            margin-bottom: 30px;
        }

        .avatar-placeholder {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            color: white;
            font-size: 3rem;
            font-weight: 700;
        }

        .profile-name {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 5px;
        }

        .profile-email {
            color: #64748b;
            font-size: 0.9rem;
        }

        .profile-menu {
            list-style: none;
            padding: 0;
        }

        .profile-menu li {
            margin-bottom: 8px;
        }

        .profile-menu a {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: #64748b;
            text-decoration: none;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .profile-menu a:hover, .profile-menu a.active {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            text-decoration: none;
        }

        /* Profile Content */
        .profile-content {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .content-header {
            padding: 30px 30px 0;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 30px;
        }

        .content-title {
            font-size: 1.8rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .content-subtitle {
            color: #64748b;
            margin-bottom: 30px;
        }

        .content-body {
            padding: 0 30px 30px;
        }

        /* Forms */
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-group.full-width {
            grid-column: 1 / -1;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
        }

        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .form-input:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .btn {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }

        .btn-primary {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            border: 2px solid rgba(37, 99, 235, 0.2);
        }

        .btn-secondary:hover {
            background: rgba(37, 99, 235, 0.2);
            color: #1d4ed8;
            text-decoration: none;
        }

        /* Alerts */
        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .alert-error {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .orders-table th,
        .orders-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #f1f5f9;
        }

        .orders-table th {
            background: #f8fafc;
            font-weight: 600;
            color: #374151;
        }

        .order-status {
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .status-completed {
            background: #f0fdf4;
            color: #16a34a;
        }

        .status-pending {
            background: #fef3c7;
            color: #d97706;
        }

        /* Tabs */
        .tabs {
            display: flex;
            border-bottom: 1px solid #f1f5f9;
            margin-bottom: 30px;
        }

        .tab {
            padding: 12px 24px;
            cursor: pointer;
            border-bottom: 2px solid transparent;
            color: #64748b;
            transition: all 0.3s ease;
        }

        .tab.active {
            color: #2563eb;
            border-bottom-color: #2563eb;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .profile-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .navbar-nav {
                display: none;
            }
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="index.php" class="navbar-brand">
                <img src="assets/logo.png" alt="FABIAN Logo">
                <span class="brand-text">FABIAN</span>
            </a>
            
            <ul class="navbar-nav">
                <li><a href="index.php" class="nav-link">Inicio</a></li>
                <li><a href="store.php" class="nav-link">Productos</a></li>
                <li><a href="perfil.php" class="nav-link active">Mi Perfil</a></li>
                <li><a href="carro.php" class="nav-link">Carrito</a></li>
                <li><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Mi Perfil</h1>
                <p class="page-subtitle">Gestiona tu información personal y preferencias</p>
            </div>

            <div class="profile-layout">
                <!-- Profile Sidebar -->
                <div class="profile-sidebar">
                    <div class="profile-avatar">
                        <div class="avatar-placeholder">
                            <?php echo strtoupper(substr($user['nombre'], 0, 1) . substr($user['apellidos'], 0, 1)); ?>
                        </div>
                        <div class="profile-name"><?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']); ?></div>
                        <div class="profile-email"><?php echo htmlspecialchars($user['email']); ?></div>
                    </div>
                    
                    <ul class="profile-menu">
                        <li><a href="#" class="tab-link active" data-tab="profile"><span class="material-symbols-rounded">person</span> Información Personal</a></li>
                        <li><a href="#" class="tab-link" data-tab="password"><span class="material-symbols-rounded">lock</span> Cambiar Contraseña</a></li>
                        <li><a href="#" class="tab-link" data-tab="orders"><span class="material-symbols-rounded">receipt_long</span> Mis Pedidos</a></li>
                        <li><a href="carro.php"><span class="material-symbols-rounded">shopping_cart</span> Mi Carrito</a></li>
                        <li><a href="logout.php"><span class="material-symbols-rounded">logout</span> Cerrar Sesión</a></li>
                    </ul>
                </div>

                <!-- Profile Content -->
                <div class="profile-content">
                    <?php if ($error): ?>
                        <div class="alert alert-error">
                            <span class="material-symbols-rounded">error</span>
                            <?php echo htmlspecialchars($error); ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <span class="material-symbols-rounded">check_circle</span>
                            <?php echo htmlspecialchars($success); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Profile Tab -->
                    <div class="tab-content active" id="profile">
                        <div class="content-header">
                            <h2 class="content-title">
                                <span class="material-symbols-rounded">person</span>
                                Información Personal
                            </h2>
                            <p class="content-subtitle">Actualiza tu información personal y datos de contacto</p>
                        </div>
                        
                        <div class="content-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="update_profile">
                                
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label class="form-label">Nombres</label>
                                        <input type="text" name="nombre" class="form-input" value="<?php echo htmlspecialchars($user['nombre']); ?>" required>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label class="form-label">Apellidos</label>
                                        <input type="text" name="apellidos" class="form-input" value="<?php echo htmlspecialchars($user['apellidos']); ?>" required>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Correo Electrónico</label>
                                    <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Teléfono</label>
                                    <input type="tel" name="telefono" class="form-input" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>">
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Dirección</label>
                                    <input type="text" name="direccion" class="form-input" value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>">
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <span class="material-symbols-rounded">save</span>
                                    Guardar Cambios
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Password Tab -->
                    <div class="tab-content" id="password">
                        <div class="content-header">
                            <h2 class="content-title">
                                <span class="material-symbols-rounded">lock</span>
                                Cambiar Contraseña
                            </h2>
                            <p class="content-subtitle">Actualiza tu contraseña para mantener tu cuenta segura</p>
                        </div>
                        
                        <div class="content-body">
                            <form method="POST">
                                <input type="hidden" name="action" value="change_password">
                                
                                <div class="form-group">
                                    <label class="form-label">Contraseña Actual</label>
                                    <input type="password" name="current_password" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Nueva Contraseña</label>
                                    <input type="password" name="new_password" class="form-input" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Confirmar Nueva Contraseña</label>
                                    <input type="password" name="confirm_password" class="form-input" required>
                                </div>
                                
                                <button type="submit" class="btn btn-primary">
                                    <span class="material-symbols-rounded">security</span>
                                    Cambiar Contraseña
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Orders Tab -->
                    <div class="tab-content" id="orders">
                        <div class="content-header">
                            <h2 class="content-title">
                                <span class="material-symbols-rounded">receipt_long</span>
                                Mis Pedidos
                            </h2>
                            <p class="content-subtitle">Historial de tus compras y pedidos realizados</p>
                        </div>
                        
                        <div class="content-body">
                            <?php if (empty($recent_orders)): ?>
                                <div style="text-align: center; padding: 40px; color: #64748b;">
                                    <span class="material-symbols-rounded" style="font-size: 3rem; margin-bottom: 20px; opacity: 0.5;">receipt_long</span>
                                    <h3>No tienes pedidos aún</h3>
                                    <p>Cuando realices tu primera compra, aparecerá aquí</p>
                                    <a href="store.php" class="btn btn-primary" style="margin-top: 20px;">
                                        <span class="material-symbols-rounded">shopping_bag</span>
                                        Ir de Compras
                                    </a>
                                </div>
                            <?php else: ?>
                                <table class="orders-table">
                                    <thead>
                                        <tr>
                                            <th>Pedido #</th>
                                            <th>Fecha</th>
                                            <th>Items</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_orders as $order): ?>
                                            <tr>
                                                <td>#<?php echo $order['id_venta']; ?></td>
                                                <td><?php echo date('d/m/Y', strtotime($order['fecha'])); ?></td>
                                                <td><?php echo $order['total_items']; ?> items</td>
                                                <td>S/. <?php echo number_format($order['total'], 2); ?></td>
                                                <td>
                                                    <span class="order-status status-completed">Completado</span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab functionality
        document.querySelectorAll('.tab-link').forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                
                // Remove active class from all tabs and contents
                document.querySelectorAll('.tab-link').forEach(l => l.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
                
                // Add active class to clicked tab
                this.classList.add('active');
                
                // Show corresponding content
                const tabId = this.getAttribute('data-tab');
                document.getElementById(tabId).classList.add('active');
            });
        });

        // Form validation
        document.querySelectorAll('form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const inputs = this.querySelectorAll('input[required]');
                let isValid = true;
                
                inputs.forEach(input => {
                    if (!input.value.trim()) {
                        isValid = false;
                        input.style.borderColor = '#dc2626';
                    } else {
                        input.style.borderColor = '#e5e7eb';
                    }
                });
                
                if (!isValid) {
                    e.preventDefault();
                    alert('Por favor completa todos los campos obligatorios');
                }
            });
        });
    </script>
</body>
</html>
