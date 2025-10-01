<?php
session_start();
include_once("conexionSQL/conexion.php");

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
if ($id <= 0) {
    header('Location: store.php');
    exit;
}

// Fetch product with category
$stmt = $conexion->prepare("SELECT p.*, c.nombre AS categoria_nombre FROM productos p LEFT JOIN categorias c ON p.id_categoria = c.id_categoria WHERE p.id_producto = ? AND p.estado='1' LIMIT 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$producto = $res ? $res->fetch_assoc() : null;

if (!$producto) {
    header('Location: store.php');
    exit;
}

// Related products (same category)
$relacionados = [];
if (!empty($producto['id_categoria'])) {
    $stmt2 = $conexion->prepare("SELECT id_producto, nombre, precio, imagen FROM productos WHERE id_categoria = ? AND id_producto <> ? AND estado='1' ORDER BY RAND() LIMIT 4");
    $stmt2->bind_param("ii", $producto['id_categoria'], $id);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    if ($res2) { while($r = $res2->fetch_assoc()){ $relacionados[] = $r; } }
}

function getImageSrc($raw) {
    if (!$raw) return 'assets/logo.png';
    if (preg_match('/^https?:\/\//i', $raw)) return $raw;
    if (strpos($raw,'/') !== false) return $raw;
    return 'img/'.$raw;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($producto['nombre']); ?> | FABIAN Publicidad</title>
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

        .nav-link:hover {
            color: #2563eb;
            background: rgba(37, 99, 235, 0.1);
            text-decoration: none;
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

        /* Breadcrumb */
        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 30px;
            color: #64748b;
            font-size: 0.9rem;
        }

        .breadcrumb a {
            color: #2563eb;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb a:hover {
            color: #1d4ed8;
        }

        /* Product Section */
        .product-section {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            margin-bottom: 50px;
        }

        .product-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 60px;
            padding: 50px;
        }

        .product-image-section {
            position: relative;
        }

        .product-image {
            width: 100%;
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.02);
        }

        .product-info-section {
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .product-category {
            color: #2563eb;
            font-weight: 500;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
        }

        .product-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 20px;
            line-height: 1.2;
        }

        .product-description {
            color: #64748b;
            font-size: 1.1rem;
            line-height: 1.7;
            margin-bottom: 30px;
        }

        .product-price {
            font-size: 2.5rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 30px;
        }

        .product-actions {
            display: flex;
            gap: 15px;
            margin-bottom: 30px;
        }

        .btn-primary {
            flex: 1;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 16px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1.1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-secondary {
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            border: 2px solid rgba(37, 99, 235, 0.2);
            padding: 14px 20px;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
        }

        .btn-secondary:hover {
            background: rgba(37, 99, 235, 0.2);
            color: #1d4ed8;
            text-decoration: none;
        }

        .product-features {
            background: #f8fafc;
            border-radius: 12px;
            padding: 20px;
        }

        .features-title {
            font-weight: 600;
            color: #374151;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .features-list {
            list-style: none;
            padding: 0;
        }

        .features-list li {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 8px 0;
            color: #64748b;
        }

        .features-list li .material-symbols-rounded {
            color: #2563eb;
            font-size: 1.2rem;
        }

        /* Related Products */
        .related-section {
            margin-top: 80px;
        }

        .section-title {
            font-size: 2rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 40px;
            text-align: center;
            position: relative;
        }

        .section-title::after {
            content: '';
            position: absolute;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            width: 60px;
            height: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            border-radius: 2px;
        }

        .related-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
        }

        .related-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
        }

        .related-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(37, 99, 235, 0.15);
        }

        .related-image {
            width: 100%;
            height: 180px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .related-card:hover .related-image {
            transform: scale(1.05);
        }

        .related-info {
            padding: 20px;
        }

        .related-name {
            font-weight: 600;
            color: #374151;
            margin-bottom: 8px;
            font-size: 1rem;
        }

        .related-price {
            color: #2563eb;
            font-weight: 700;
            font-size: 1.1rem;
        }

        /* Quantity Selector */
        .quantity-selector {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 20px;
        }

        .quantity-label {
            font-weight: 500;
            color: #374151;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }

        .quantity-btn {
            background: #f9fafb;
            border: none;
            padding: 10px 15px;
            cursor: pointer;
            transition: background 0.3s ease;
            color: #64748b;
        }

        .quantity-btn:hover {
            background: #2563eb;
            color: white;
        }

        .quantity-input {
            border: none;
            padding: 10px 15px;
            text-align: center;
            width: 60px;
            background: white;
            color: #374151;
            font-weight: 500;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .navbar-nav {
                display: none;
            }

            .product-container {
                grid-template-columns: 1fr;
                gap: 30px;
                padding: 30px 20px;
            }

            .product-title {
                font-size: 2rem;
            }

            .product-price {
                font-size: 2rem;
            }

            .product-actions {
                flex-direction: column;
            }

            .related-grid {
                grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                gap: 20px;
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
                <li><a href="#" class="nav-link">Categorías</a></li>
                <li><a href="#" class="nav-link">Contacto</a></li>
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
            <!-- Breadcrumb -->
            <div class="breadcrumb">
                <a href="index.php">Inicio</a>
                <span class="material-symbols-rounded">chevron_right</span>
                <a href="store.php">Productos</a>
                <span class="material-symbols-rounded">chevron_right</span>
                <?php if (!empty($producto['categoria_nombre'])): ?>
                    <a href="store.php?categoria=<?php echo $producto['id_categoria']; ?>"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></a>
                    <span class="material-symbols-rounded">chevron_right</span>
                <?php endif; ?>
                <span><?php echo htmlspecialchars($producto['nombre']); ?></span>
            </div>

            <!-- Product Section -->
            <div class="product-section">
                <div class="product-container">
                    <div class="product-image-section">
                        <img src="<?php echo htmlspecialchars(getImageSrc($producto['imagen'])); ?>" 
                             alt="<?php echo htmlspecialchars($producto['nombre']); ?>" 
                             class="product-image"
                             onerror="this.src='assets/logo.png';">
                    </div>
                    
                    <div class="product-info-section">
                        <?php if (!empty($producto['categoria_nombre'])): ?>
                            <div class="product-category"><?php echo htmlspecialchars($producto['categoria_nombre']); ?></div>
                        <?php endif; ?>
                        
                        <h1 class="product-title"><?php echo htmlspecialchars($producto['nombre']); ?></h1>
                        
                        <?php if (!empty($producto['descripcion'])): ?>
                            <p class="product-description"><?php echo htmlspecialchars($producto['descripcion']); ?></p>
                        <?php endif; ?>
                        
                        <div class="product-price">S/. <?php echo number_format($producto['precio'], 2); ?></div>
                        
                        <div class="quantity-selector">
                            <span class="quantity-label">Cantidad:</span>
                            <div class="quantity-controls">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(-1)">
                                    <span class="material-symbols-rounded">remove</span>
                                </button>
                                <input type="number" id="quantity" class="quantity-input" value="1" min="1" max="100">
                                <button type="button" class="quantity-btn" onclick="changeQuantity(1)">
                                    <span class="material-symbols-rounded">add</span>
                                </button>
                            </div>
                        </div>
                        
                        <div class="product-actions">
                            <button class="btn-primary" onclick="addToCart(<?php echo $producto['id_producto']; ?>)">
                                <span class="material-symbols-rounded">add_shopping_cart</span>
                                Agregar al Carrito
                            </button>
                            <a href="store.php" class="btn-secondary">
                                <span class="material-symbols-rounded">arrow_back</span>
                            </a>
                        </div>
                        
                        <div class="product-features">
                            <h4 class="features-title">
                                <span class="material-symbols-rounded">verified</span>
                                Características
                            </h4>
                            <ul class="features-list">
                                <li>
                                    <span class="material-symbols-rounded">local_shipping</span>
                                    Envío gratuito en pedidos mayores a S/. 100
                                </li>
                                <li>
                                    <span class="material-symbols-rounded">palette</span>
                                    Personalización disponible
                                </li>
                                <li>
                                    <span class="material-symbols-rounded">high_quality</span>
                                    Productos de alta calidad
                                </li>
                                <li>
                                    <span class="material-symbols-rounded">support_agent</span>
                                    Soporte técnico incluido
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Related Products -->
            <?php if (!empty($relacionados)): ?>
                <div class="related-section">
                    <h2 class="section-title">Productos Relacionados</h2>
                    <div class="related-grid">
                        <?php foreach ($relacionados as $rel): ?>
                            <a href="product.php?id=<?php echo $rel['id_producto']; ?>" class="related-card">
                                <img src="<?php echo htmlspecialchars(getImageSrc($rel['imagen'])); ?>" 
                                     alt="<?php echo htmlspecialchars($rel['nombre']); ?>" 
                                     class="related-image"
                                     onerror="this.src='assets/logo.png';">
                                <div class="related-info">
                                    <h4 class="related-name"><?php echo htmlspecialchars($rel['nombre']); ?></h4>
                                    <p class="related-price">S/. <?php echo number_format($rel['precio'], 2); ?></p>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <script>
        function changeQuantity(delta) {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value) || 1;
            const newValue = Math.max(1, Math.min(100, currentValue + delta));
            input.value = newValue;
        }

        function addToCart(productId) {
            const quantity = document.getElementById('quantity').value;
            
            fetch('add_to_cart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: `id_producto=${productId}&cantidad=${quantity}`
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
                window.location.href = `add_to_cart.php?id_producto=${productId}&cantidad=${quantity}`;
            });
        }

        // Quantity input validation
        document.getElementById('quantity').addEventListener('input', function() {
            const value = parseInt(this.value);
            if (isNaN(value) || value < 1) {
                this.value = 1;
            } else if (value > 100) {
                this.value = 100;
            }
        });
    </script>
</body>
</html>
