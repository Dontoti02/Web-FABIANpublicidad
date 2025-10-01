<?php
session_start();
include_once("conexionSQL/conexion.php");

$categoriaId = isset($_GET['categoria']) ? intval($_GET['categoria']) : 0;
$search = trim($_GET['q'] ?? '');
$sortBy = $_GET['sort'] ?? 'newest';
$minPrice = isset($_GET['min_price']) ? floatval($_GET['min_price']) : 0;
$maxPrice = isset($_GET['max_price']) ? floatval($_GET['max_price']) : 0;
$page = max(1, intval($_GET['page'] ?? 1));
$perPage = 12;
$offset = ($page - 1) * $perPage;

// Fetch categories for filter
$cats = [];
$resCats = $conexion->query("SELECT id_categoria, nombre FROM categorias WHERE estado='1' ORDER BY nombre ASC");
if ($resCats) {
    while ($row = $resCats->fetch_assoc()) { $cats[] = $row; }
}

// Build products query
$params = [];
$countParams = [];
$sql = "SELECT * FROM productos WHERE estado='1'";
$countSql = "SELECT COUNT(*) as total FROM productos WHERE estado='1'";

if ($categoriaId > 0) {
    $sql .= " AND id_categoria = ?";
    $countSql .= " AND id_categoria = ?";
    $params[] = ["i", $categoriaId];
    $countParams[] = ["i", $categoriaId];
}

if (!empty($search)) {
    $sql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
    $countSql .= " AND (nombre LIKE ? OR descripcion LIKE ?)";
    $searchParam = "%$search%";
    $params[] = ["s", $searchParam];
    $params[] = ["s", $searchParam];
    $countParams[] = ["s", $searchParam];
    $countParams[] = ["s", $searchParam];
}

if ($minPrice > 0) {
    $sql .= " AND precio >= ?";
    $countSql .= " AND precio >= ?";
    $params[] = ["d", $minPrice];
    $countParams[] = ["d", $minPrice];
}

if ($maxPrice > 0) {
    $sql .= " AND precio <= ?";
    $countSql .= " AND precio <= ?";
    $params[] = ["d", $maxPrice];
    $countParams[] = ["d", $maxPrice];
}

// Sorting
switch ($sortBy) {
    case 'price_asc':
        $sql .= " ORDER BY precio ASC";
        break;
    case 'price_desc':
        $sql .= " ORDER BY precio DESC";
        break;
    case 'name':
        $sql .= " ORDER BY nombre ASC";
        break;
    default:
        $sql .= " ORDER BY id_producto DESC";
}

// Get total count
$countStmt = $conexion->prepare($countSql);
if (!empty($countParams)) {
    $types = '';
    $values = [];
    foreach ($countParams as $param) {
        $types .= $param[0];
        $values[] = $param[1];
    }
    $countStmt->bind_param($types, ...$values);
}
$countStmt->execute();
$totalProducts = $countStmt->get_result()->fetch_assoc()['total'];
$totalPages = ceil($totalProducts / $perPage);

// Add pagination
$sql .= " LIMIT ? OFFSET ?";
$params[] = ["i", $perPage];
$params[] = ["i", $offset];

// Execute query
$productos = [];
$stmt = $conexion->prepare($sql);
if (!empty($params)) {
    $types = '';
    $values = [];
    foreach ($params as $param) {
        $types .= $param[0];
        $values[] = $param[1];
    }
    $stmt->bind_param($types, ...$values);
}
$stmt->execute();
$result = $stmt->get_result();
if ($result) { 
    while ($r = $result->fetch_assoc()) { 
        $productos[] = $r; 
    } 
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tienda | FABIAN Publicidad</title>
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
        }

        .cart-icon {
            position: relative;
            color: #64748b;
            font-size: 1.2rem;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .cart-icon:hover {
            color: #2563eb;
            text-decoration: none;
        }

        .cart-count {
            position: absolute;
            top: -8px;
            right: -8px;
            background: #2563eb;
            color: white;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            font-size: 0.75rem;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
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

        /* Filters Section */
        .filters-section {
            background: white;
            border-radius: 16px;
            padding: 30px;
            margin-bottom: 40px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
        }

        .filters-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            align-items: end;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .filter-label {
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
        }

        .filter-input, .filter-select {
            padding: 12px 16px;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .filter-input:focus, .filter-select:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .filter-btn {
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .filter-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        /* Products Grid */
        .products-section {
            margin-bottom: 60px;
        }

        .products-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }

        .products-count {
            color: #64748b;
            font-size: 1rem;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            position: relative;
        }

        .product-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            transform: scaleX(0);
            transition: transform 0.3s ease;
        }

        .product-card:hover::before {
            transform: scaleX(1);
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.15);
        }

        .product-image {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-card:hover .product-image {
            transform: scale(1.05);
        }

        .product-info {
            padding: 24px;
        }

        .product-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            line-height: 1.4;
        }

        .product-description {
            color: #64748b;
            font-size: 0.9rem;
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 16px;
        }

        .product-actions {
            display: flex;
            gap: 10px;
        }

        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
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
            padding: 10px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .btn-secondary:hover {
            background: rgba(37, 99, 235, 0.2);
        }

        /* Pagination */
        .pagination {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 10px;
            margin-top: 50px;
        }

        .pagination a, .pagination span {
            padding: 12px 16px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .pagination a {
            background: white;
            color: #64748b;
            border: 1px solid #e5e7eb;
        }

        .pagination a:hover {
            background: #2563eb;
            color: white;
            border-color: #2563eb;
        }

        .pagination .current {
            background: #2563eb;
            color: white;
            border: 1px solid #2563eb;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: #64748b;
        }

        .empty-state .material-symbols-rounded {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-nav {
                display: none;
            }

            .filters-grid {
                grid-template-columns: 1fr;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .page-title {
                font-size: 2rem;
            }

            .products-header {
                flex-direction: column;
                gap: 15px;
                align-items: stretch;
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
                <li><a href="store.php" class="nav-link active">Productos</a></li>
                <?php if (isset($_SESSION['cliente_id'])): ?>
                    <li><a href="perfil.php" class="nav-link">Mi Perfil</a></li>
                    <li><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
                <?php else: ?>
                    <li><a href="login.php" class="nav-link">Iniciar Sesión</a></li>
                    <li><a href="register.php" class="nav-link">Registrarse</a></li>
                <?php endif; ?>
                <li>
                    <a href="carro.php" class="cart-icon">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <span class="cart-count">
                            <?php
                            $count = 0;
                            if (isset($_SESSION['cart'])) {
                                foreach ($_SESSION['cart'] as $item) {
                                    $count += (int)$item['cantidad'];
                                }
                            }
                            echo $count;
                            ?>
                        </span>
                    </a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Nuestra Tienda</h1>
                <p class="page-subtitle">Descubre nuestros productos promocionales y publicitarios</p>
            </div>

            <!-- Filters Section -->
            <div class="filters-section">
                <form method="GET" action="store.php">
                    <div class="filters-grid">
                        <div class="filter-group">
                            <label class="filter-label">Buscar productos</label>
                            <input type="text" name="q" class="filter-input" placeholder="Nombre del producto..." value="<?php echo htmlspecialchars($search); ?>">
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Categoría</label>
                            <select name="categoria" class="filter-select">
                                <option value="">Todas las categorías</option>
                                <?php foreach ($cats as $cat): ?>
                                    <option value="<?php echo $cat['id_categoria']; ?>" <?php echo $categoriaId == $cat['id_categoria'] ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($cat['nombre']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Ordenar por</label>
                            <select name="sort" class="filter-select">
                                <option value="newest" <?php echo $sortBy == 'newest' ? 'selected' : ''; ?>>Más recientes</option>
                                <option value="name" <?php echo $sortBy == 'name' ? 'selected' : ''; ?>>Nombre A-Z</option>
                                <option value="price_asc" <?php echo $sortBy == 'price_asc' ? 'selected' : ''; ?>>Precio menor</option>
                                <option value="price_desc" <?php echo $sortBy == 'price_desc' ? 'selected' : ''; ?>>Precio mayor</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">&nbsp;</label>
                            <button type="submit" class="filter-btn">
                                <span class="material-symbols-rounded">search</span>
                                Buscar
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Products Section -->
            <div class="products-section">
                <div class="products-header">
                    <div class="products-count">
                        Mostrando <?php echo count($productos); ?> de <?php echo $totalProducts; ?> productos
                    </div>
                </div>

                <?php if (empty($productos)): ?>
                    <div class="empty-state">
                        <span class="material-symbols-rounded">inventory_2</span>
                        <h3>No se encontraron productos</h3>
                        <p>Intenta ajustar los filtros de búsqueda</p>
                    </div>
                <?php else: ?>
                    <div class="products-grid">
                        <?php foreach ($productos as $producto): ?>
                            <div class="product-card">
                                <?php
                                $rawProdImg = $producto['imagen'] ?? '';
                                $prodImgSrc = '';
                                if ($rawProdImg) {
                                    if (preg_match('/^https?:\/\//i', $rawProdImg)) {
                                        $prodImgSrc = $rawProdImg;
                                    } else if (strpos($rawProdImg, '/') !== false) {
                                        $prodImgSrc = $rawProdImg;
                                    } else {
                                        $prodImgSrc = 'img/' . $rawProdImg;
                                    }
                                }
                                if (!$prodImgSrc) {
                                    $prodImgSrc = 'assets/logo.png';
                                }
                                ?>
                                <img src="<?php echo htmlspecialchars($prodImgSrc); ?>" alt="<?php echo htmlspecialchars($producto['nombre']); ?>" class="product-image" onerror="this.src='assets/logo.png';">
                                
                                <div class="product-info">
                                    <h4 class="product-name"><?php echo htmlspecialchars($producto['nombre']); ?></h4>
                                    <?php if (!empty($producto['descripcion'])): ?>
                                        <p class="product-description"><?php echo htmlspecialchars(substr($producto['descripcion'], 0, 100)) . (strlen($producto['descripcion']) > 100 ? '...' : ''); ?></p>
                                    <?php endif; ?>
                                    <p class="product-price">S/. <?php echo number_format($producto['precio'], 2); ?></p>
                                    
                                    <div class="product-actions">
                                        <button class="btn-primary" onclick="addToCart(<?php echo $producto['id_producto']; ?>)">
                                            <span class="material-symbols-rounded">add_shopping_cart</span>
                                            Agregar al Carrito
                                        </button>
                                        <a href="product.php?id=<?php echo $producto['id_producto']; ?>" class="btn-secondary">
                                            <span class="material-symbols-rounded">visibility</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Pagination -->
                    <?php if ($totalPages > 1): ?>
                        <div class="pagination">
                            <?php if ($page > 1): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page - 1])); ?>">
                                    <span class="material-symbols-rounded">chevron_left</span>
                                </a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $page - 2); $i <= min($totalPages, $page + 2); $i++): ?>
                                <?php if ($i == $page): ?>
                                    <span class="current"><?php echo $i; ?></span>
                                <?php else: ?>
                                    <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $i])); ?>"><?php echo $i; ?></a>
                                <?php endif; ?>
                            <?php endfor; ?>
                            
                            <?php if ($page < $totalPages): ?>
                                <a href="?<?php echo http_build_query(array_merge($_GET, ['page' => $page + 1])); ?>">
                                    <span class="material-symbols-rounded">chevron_right</span>
                                </a>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script>
        function addToCart(productId) {
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_producto=${productId}&cantidad=1`
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update cart count
                    location.reload();
                } else {
                    alert('Error al agregar al carrito');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                // Fallback: redirect to add_to_cart.php
                window.location.href = `add_to_cart.php?id_producto=${productId}&cantidad=1`;
            });
        }

        // Auto-submit form on select change
        document.querySelectorAll('.filter-select').forEach(select => {
            select.addEventListener('change', function() {
                this.form.submit();
            });
        });
    </script>
</body>
</html>
