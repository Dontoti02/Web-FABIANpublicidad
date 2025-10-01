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