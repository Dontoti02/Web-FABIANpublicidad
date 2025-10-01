<?php
session_start();
include_once("conexionSQL/conexion.php");

// Get categories
$sqlCategorias = "SELECT * FROM categorias WHERE estado='1' LIMIT 8";
$resultCategorias = $conexion->query($sqlCategorias);

// Get featured products
$sqlProductos = "SELECT * FROM productos WHERE estado='1' ORDER BY id_producto DESC LIMIT 8";
$resultProductos = $conexion->query($sqlProductos);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FABIAN | Productos Promocionales y Publicitarios</title>
    <meta name="description" content="FABIAN - Tu empresa especializada en productos promocionales y publicitarios personalizados. Lapiceros, tazas, memorias USB, llaveros y más.">
    <meta name="keywords" content="FABIAN, productos promocionales, publicitarios, personalizados, lapiceros, tazas, memorias USB, llaveros, bolsos, mochilas">
    <meta name="author" content="FABIAN">
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
        }

        .cart-icon {
            position: relative;
            color: #64748b;
            font-size: 1.2rem;
            transition: color 0.3s ease;
        }

        .cart-icon:hover {
            color: #2563eb;
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

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 120px 0 80px;
            margin-top: 70px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1000 1000"><defs><radialGradient id="a" cx="50%" cy="50%"><stop offset="0%" stop-color="%23ffffff" stop-opacity="0.1"/><stop offset="100%" stop-color="%23ffffff" stop-opacity="0"/></radialGradient></defs><circle cx="200" cy="200" r="100" fill="url(%23a)"/><circle cx="800" cy="300" r="150" fill="url(%23a)"/><circle cx="400" cy="700" r="120" fill="url(%23a)"/></svg>') no-repeat center center;
            background-size: cover;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-20px) rotate(5deg); }
        }

        .hero-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .hero-title {
            font-size: 3.5rem;
            font-weight: 700;
            color: white;
            margin-bottom: 20px;
            text-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        }

        .hero-subtitle {
            font-size: 1.3rem;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 40px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
        }

        .hero-cta {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            background: rgba(255, 255, 255, 0.95);
            color: #2563eb;
            padding: 16px 32px;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }

        .hero-cta:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(0, 0, 0, 0.3);
            color: #1d4ed8;
            text-decoration: none;
        }

        /* Categories Section */
        .categories-section {
            padding: 80px 0;
            background: white;
        }

        .section-title {
            text-align: center;
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 60px;
            position: relative;
        }

        .section-title::after {
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

        .categories-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .category-card {
            background: white;
            border-radius: 16px;
            padding: 30px 20px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
            position: relative;
            overflow: hidden;
        }

        .category-card::before {
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

        .category-card:hover::before {
            transform: scaleX(1);
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.15);
            border-color: rgba(37, 99, 235, 0.2);
        }

        .category-image {
            width: 80px;
            height: 80px;
            object-fit: contain;
            margin: 0 auto 20px;
            border-radius: 12px;
            transition: transform 0.3s ease;
        }

        .category-card:hover .category-image {
            transform: scale(1.1);
        }

        .category-name {
            font-size: 1.1rem;
            font-weight: 600;
            color: #374151;
            margin: 0;
        }

        /* Products Section */
        .products-section {
            padding: 80px 0;
            background: #f8fafc;
        }

        .products-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }

        .product-card {
            background: white;
            border-radius: 16px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            border: 1px solid #f1f5f9;
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
        }

        .product-price {
            font-size: 1.3rem;
            font-weight: 700;
            color: #2563eb;
            margin-bottom: 16px;
        }

        .add-to-cart {
            width: 100%;
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
        }

        .add-to-cart:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(37, 99, 235, 0.4);
        }

        /* Footer */
        .footer {
            background: #1e293b;
            color: #94a3b8;
            padding: 60px 0 30px;
        }

        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 40px;
        }

        .footer-section h4 {
            color: white;
            font-weight: 600;
            margin-bottom: 20px;
            font-size: 1.2rem;
        }

        .footer-section p, .footer-section a {
            color: #94a3b8;
            text-decoration: none;
            line-height: 1.8;
            transition: color 0.3s ease;
        }

        .footer-section a:hover {
            color: #2563eb;
        }

        .footer-bottom {
            border-top: 1px solid #334155;
            margin-top: 40px;
            padding-top: 30px;
            text-align: center;
            color: #64748b;
        }

        /* Mobile Menu */
        .mobile-menu-toggle {
            display: none;
            background: none;
            border: none;
            color: #64748b;
            font-size: 1.5rem;
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav {
                display: none;
            }

            .mobile-menu-toggle {
                display: block;
            }

            .hero-title {
                font-size: 2.5rem;
            }

            .hero-subtitle {
                font-size: 1.1rem;
            }

            .categories-grid {
                grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
                gap: 20px;
            }

            .products-grid {
                grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
                gap: 20px;
            }

            .section-title {
                font-size: 2rem;
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
            
            <button class="mobile-menu-toggle">
                <span class="material-symbols-rounded">menu</span>
            </button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="hero-content">
            <h1 class="hero-title">Bienvenido a <span style="color: #dbeafe;">FABIAN</span></h1>
            <p class="hero-subtitle">Tu empresa especializada en productos promocionales y publicitarios personalizados</p>
            <a href="store.php" class="hero-cta">
                <span class="material-symbols-rounded">storefront</span>
                Explorar Productos
            </a>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="categories-section" id="categorias">
        <div class="container">
            <h2 class="section-title">Nuestras Categorías</h2>
            <div class="categories-grid">
                <?php if ($resultCategorias && $resultCategorias->num_rows > 0): ?>
                    <?php while ($categoria = $resultCategorias->fetch_assoc()): ?>
                        <div class="category-card">
                            <?php
                            $rawCatImg = $categoria['imagen'] ?? '';
                            $catImgSrc = '';
                            if ($rawCatImg) {
                                if (preg_match('/^https?:\/\//i', $rawCatImg)) {
                                    $catImgSrc = $rawCatImg;
                                } else if (strpos($rawCatImg, '/') !== false) {
                                    $catImgSrc = $rawCatImg;
                                } else {
                                    $catImgSrc = 'img/' . $rawCatImg;
                                }
                            }
                            if (!$catImgSrc) {
                                $catImgSrc = 'assets/logo.png';
                            }
                            ?>
                            <img src="<?php echo htmlspecialchars($catImgSrc); ?>" alt="<?php echo htmlspecialchars($categoria['nombre']); ?>" class="category-image" onerror="this.src='assets/logo.png';">
                            <h4 class="category-name"><?php echo htmlspecialchars($categoria['nombre']); ?></h4>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Products Section -->
    <section class="products-section">
        <div class="container">
            <h2 class="section-title">Productos Destacados</h2>
            <div class="products-grid">
                <?php if ($resultProductos && $resultProductos->num_rows > 0): ?>
                    <?php while ($producto = $resultProductos->fetch_assoc()): ?>
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
                                <p class="product-price">S/. <?php echo number_format($producto['precio'], 2); ?></p>
                                <button class="add-to-cart" onclick="addToCart(<?php echo $producto['id_producto']; ?>)">
                                    <span class="material-symbols-rounded">add_shopping_cart</span>
                                    Agregar al Carrito
                                </button>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer" id="contacto">
        <div class="footer-content">
            <div class="footer-section">
                <h4>FABIAN Publicidad</h4>
                <p>Tu empresa especializada en productos promocionales y publicitarios personalizados.</p>
            </div>
            <div class="footer-section">
                <h4>Contacto</h4>
                <p><i class="fa fa-phone"></i> +51 987 654 321</p>
                <p><i class="fa fa-envelope"></i> info@fabian.com</p>
            </div>
            <div class="footer-section">
                <h4>Horarios</h4>
                <p><i class="fa fa-clock"></i> Lun - Dom: 7:00 AM - 10:00 PM</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 FABIAN Publicidad. Todos los derechos reservados.</p>
        </div>
    </footer>

    <script>
        function addToCart(productId) {
            // Add to cart functionality
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
            });
        }

        // Mobile menu toggle
        document.querySelector('.mobile-menu-toggle').addEventListener('click', function() {
            const nav = document.querySelector('.navbar-nav');
            nav.style.display = nav.style.display === 'flex' ? 'none' : 'flex';
        });

        // Smooth scrolling for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
    </script>
</body>
</html>
