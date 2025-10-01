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
            <img src="<?php echo BASE_URL; ?>assets/logo.png" alt="Logo" class="logo-admin mb-2">
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
                <a class="nav-link text-white active" href="<?php echo BASE_URL; ?>admin">
                    <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/categorias">
                    <i class="fas fa-th-large me-2"></i>Categorías
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/productos">
                    <i class="fas fa-box me-2"></i>Productos
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/usuarios">
                    <i class="fas fa-users me-2"></i>Usuarios
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white" href="<?php echo BASE_URL; ?>admin/pedidos">
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
                <p class="text-muted mb-0">Bienvenido de vuelta, <?php echo $_SESSION['nombre']; ?></p>
            </div>
            <div class="text-muted">
                <i class="fas fa-calendar me-2"></i><?php echo date('d/m/Y H:i'); ?>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Total Productos</h6>
                                <h2 class="mb-0"><?php echo $totalProductos; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-box fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-2">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Total Categorías</h6>
                                <h2 class="mb-0"><?php echo $totalCategorias; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-th-large fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Total Usuarios</h6>
                                <h2 class="mb-0"><?php echo $totalUsuarios; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-users fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card stat-card-4">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title text-white-50">Total Ventas</h6>
                                <h2 class="mb-0"><?php echo $totalVentas; ?></h2>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-shopping-cart fa-2x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ingresos Card -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card" style="background: linear-gradient(135deg, #1565c0 0%, #1976d2 100%); color: white;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="card-title text-white-50">Ingresos Totales</h6>
                                <h2 class="mb-0">S/ <?php echo number_format($totalIngresos, 2); ?></h2>
                                <small class="text-white-50">De ventas completadas</small>
                            </div>
                            <div class="align-self-center">
                                <i class="fas fa-money-bill-wave fa-3x opacity-75"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-bolt me-2"></i>Acciones Rápidas
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo BASE_URL; ?>admin/productos" class="btn btn-primary w-100">
                                    <i class="fas fa-plus me-2"></i>Nuevo Producto
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo BASE_URL; ?>admin/categorias" class="btn btn-success w-100">
                                    <i class="fas fa-plus me-2"></i>Nueva Categoría
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo BASE_URL; ?>admin/usuarios" class="btn btn-info w-100">
                                    <i class="fas fa-user-plus me-2"></i>Nuevo Usuario
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="<?php echo BASE_URL; ?>admin/pedidos" class="btn btn-warning w-100">
                                    <i class="fas fa-eye me-2"></i>Ver Pedidos
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Additional Information -->
        <div class="row">
            <!-- Productos con Bajo Stock -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-exclamation-triangle me-2 text-warning"></i>Productos con Bajo Stock
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productosBajoStock)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Producto</th>
                                            <th>Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($productosBajoStock as $producto): ?>
                                            <tr>
                                                <td><?php echo htmlspecialchars($producto['nombre']); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo $producto['stock'] <= 5 ? 'danger' : 'warning'; ?>">
                                                        <?php echo $producto['stock']; ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-check-circle fa-2x text-success mb-2"></i>
                                <p class="text-muted mb-0">Todos los productos tienen stock suficiente</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Ventas Recientes -->
            <div class="col-md-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-shopping-cart me-2 text-primary"></i>Ventas Recientes
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($ventasRecientes)): ?>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Cliente</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($ventasRecientes as $venta): ?>
                                            <tr>
                                                <td>#<?php echo $venta['id_venta']; ?></td>
                                                <td><?php echo htmlspecialchars($venta['cliente'] ?? 'Cliente N/A'); ?></td>
                                                <td>S/ <?php echo number_format($venta['total'], 2); ?></td>
                                                <td>
                                                    <?php
                                                    $badgeClass = 'secondary';
                                                    switch($venta['estado']) {
                                                        case 'completada': $badgeClass = 'success'; break;
                                                        case 'pendiente': $badgeClass = 'warning'; break;
                                                        case 'cancelada': $badgeClass = 'danger'; break;
                                                    }
                                                    ?>
                                                    <span class="badge bg-<?php echo $badgeClass; ?>">
                                                        <?php echo ucfirst($venta['estado']); ?>
                                                    </span>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-3">
                                <i class="fas fa-shopping-cart fa-2x text-muted mb-2"></i>
                                <p class="text-muted mb-0">No hay ventas recientes</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Más Vendidos -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">
                            <i class="fas fa-star me-2 text-warning"></i>Productos Más Vendidos
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($productosMasVendidos)): ?>
                            <div class="row">
                                <?php foreach ($productosMasVendidos as $index => $producto): ?>
                                    <div class="col-md-2 mb-3">
                                        <div class="text-center">
                                            <div class="badge bg-primary rounded-pill mb-2" style="font-size: 1.2rem;">
                                                #<?php echo $index + 1; ?>
                                            </div>
                                            <h6 class="mb-1"><?php echo htmlspecialchars($producto['nombre']); ?></h6>
                                            <small class="text-muted"><?php echo $producto['total_vendido']; ?> vendidos</small>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <div class="text-center py-4">
                                <i class="fas fa-chart-bar fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No hay datos de productos vendidos</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Dashboard loaded with real data from database
            console.log('Dashboard cargado con datos reales de la base de datos');
            
            // Optional: Add refresh functionality
            $('#refresh-stats').on('click', function() {
                location.reload();
            });
        });
    </script>
</body>
</html>