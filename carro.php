<?php
session_start();
include_once("conexionSQL/conexion.php");

// Check if user is logged in
if (!isset($_SESSION['cliente_id'])) {
    header('Location: login.php?redirect=carro');
    exit();
}

$error = '';
$success = '';

// Handle cart actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'update_quantity':
                $product_id = (int)$_POST['product_id'];
                $quantity = max(1, (int)$_POST['quantity']);
                
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id]['cantidad'] = $quantity;
                    $success = 'Cantidad actualizada';
                }
                break;
                
            case 'remove_item':
                $product_id = (int)$_POST['product_id'];
                if (isset($_SESSION['cart'][$product_id])) {
                    unset($_SESSION['cart'][$product_id]);
                    $success = 'Producto eliminado del carrito';
                }
                break;
                
            case 'clear_cart':
                $_SESSION['cart'] = [];
                $success = 'Carrito vaciado';
                break;
        }
    }
}

// Get cart items
$cart = $_SESSION['cart'] ?? [];
$cart_total = 0;
$cart_count = 0;

foreach ($cart as $item) {
    $cart_total += $item['precio'] * $item['cantidad'];
    $cart_count += $item['cantidad'];
}

// Get user info
$user_id = $_SESSION['cliente_id'];
$stmt = $conexion->prepare("SELECT nombre, apellidos, email FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Carrito | FABIAN Publicidad</title>
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

        /* Cart Layout */
        .cart-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }

        /* Cart Items */
        .cart-items {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .cart-header {
            padding: 30px;
            border-bottom: 1px solid #f1f5f9;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .cart-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .cart-count {
            background: #2563eb;
            color: white;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: 600;
        }

        .clear-cart-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: none;
            padding: 8px 16px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: all 0.3s ease;
        }

        .clear-cart-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        .cart-item {
            padding: 30px;
            border-bottom: 1px solid #f1f5f9;
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 20px;
            align-items: center;
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-size: 1.2rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }

        .item-price {
            font-size: 1.1rem;
            color: #2563eb;
            font-weight: 600;
            margin-bottom: 12px;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .quantity-btn {
            background: #f1f5f9;
            border: none;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            color: #64748b;
        }

        .quantity-btn:hover {
            background: #2563eb;
            color: white;
        }

        .quantity-input {
            width: 60px;
            text-align: center;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 8px;
            font-weight: 500;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            gap: 12px;
            align-items: center;
        }

        .item-total {
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
        }

        .remove-btn {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
            border: none;
            padding: 8px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .remove-btn:hover {
            background: rgba(239, 68, 68, 0.2);
        }

        /* Cart Summary */
        .cart-summary {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            padding: 30px;
            height: fit-content;
            position: sticky;
            top: 120px;
        }

        .summary-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            color: #64748b;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            font-size: 1.3rem;
            font-weight: 700;
            color: #1e293b;
            padding-top: 20px;
            border-top: 2px solid #f1f5f9;
            margin-top: 20px;
        }

        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            padding: 16px;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .checkout-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.4);
            color: white;
            text-decoration: none;
        }

        .continue-shopping {
            width: 100%;
            background: rgba(37, 99, 235, 0.1);
            color: #2563eb;
            border: 2px solid rgba(37, 99, 235, 0.2);
            padding: 12px;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-top: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            text-decoration: none;
        }

        .continue-shopping:hover {
            background: rgba(37, 99, 235, 0.2);
            color: #1d4ed8;
            text-decoration: none;
        }

        /* Empty Cart */
        .empty-cart {
            text-align: center;
            padding: 80px 40px;
            color: #64748b;
        }

        .empty-cart .material-symbols-rounded {
            font-size: 4rem;
            margin-bottom: 20px;
            opacity: 0.5;
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

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .cart-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .cart-item {
                grid-template-columns: 80px 1fr;
                gap: 15px;
            }

            .item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                margin-top: 15px;
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
                <li><a href="perfil.php" class="nav-link">Mi Perfil</a></li>
                <li><a href="carro.php" class="nav-link active">Carrito</a></li>
                <li><a href="logout.php" class="nav-link">Cerrar Sesión</a></li>
            </ul>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Mi Carrito</h1>
                <p class="page-subtitle">Revisa y confirma tus productos antes de proceder al checkout</p>
            </div>

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

            <?php if (empty($cart)): ?>
                <!-- Empty Cart -->
                <div class="cart-items">
                    <div class="empty-cart">
                        <span class="material-symbols-rounded">shopping_cart</span>
                        <h3>Tu carrito está vacío</h3>
                        <p>Agrega algunos productos para comenzar tu compra</p>
                        <a href="store.php" class="checkout-btn" style="max-width: 300px; margin: 30px auto 0;">
                            <span class="material-symbols-rounded">shopping_bag</span>
                            Ir de Compras
                        </a>
                    </div>
                </div>
            <?php else: ?>
                <div class="cart-layout">
                    <!-- Cart Items -->
                    <div class="cart-items">
                        <div class="cart-header">
                            <h2 class="cart-title">
                                <span class="material-symbols-rounded">shopping_cart</span>
                                Productos
                                <span class="cart-count"><?php echo $cart_count; ?></span>
                            </h2>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="clear_cart">
                                <button type="submit" class="clear-cart-btn" onclick="return confirm('¿Estás seguro de vaciar el carrito?')">
                                    <span class="material-symbols-rounded">delete</span>
                                    Vaciar Carrito
                                </button>
                            </form>
                        </div>

                        <?php foreach ($cart as $product_id => $item): ?>
                            <div class="cart-item">
                                <img src="<?php echo htmlspecialchars($item['imagen'] ?: 'assets/logo.png'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                     class="item-image"
                                     onerror="this.src='assets/logo.png';">
                                
                                <div class="item-details">
                                    <h4 class="item-name"><?php echo htmlspecialchars($item['nombre']); ?></h4>
                                    <p class="item-price">S/. <?php echo number_format($item['precio'], 2); ?></p>
                                    
                                    <div class="quantity-controls">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $product_id; ?>, -1)">
                                            <span class="material-symbols-rounded">remove</span>
                                        </button>
                                        <input type="number" 
                                               class="quantity-input" 
                                               value="<?php echo $item['cantidad']; ?>" 
                                               min="1" 
                                               id="qty-<?php echo $product_id; ?>"
                                               onchange="updateQuantityDirect(<?php echo $product_id; ?>, this.value)">
                                        <button type="button" class="quantity-btn" onclick="updateQuantity(<?php echo $product_id; ?>, 1)">
                                            <span class="material-symbols-rounded">add</span>
                                        </button>
                                    </div>
                                </div>
                                
                                <div class="item-actions">
                                    <div class="item-total">S/. <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></div>
                                    <form method="POST" style="display: inline;">
                                        <input type="hidden" name="action" value="remove_item">
                                        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>">
                                        <button type="submit" class="remove-btn" onclick="return confirm('¿Eliminar este producto?')">
                                            <span class="material-symbols-rounded">delete</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Cart Summary -->
                    <div class="cart-summary">
                        <h3 class="summary-title">
                            <span class="material-symbols-rounded">receipt</span>
                            Resumen del Pedido
                        </h3>
                        
                        <div class="summary-row">
                            <span>Subtotal (<?php echo $cart_count; ?> items)</span>
                            <span>S/. <?php echo number_format($cart_total, 2); ?></span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Envío</span>
                            <span><?php echo $cart_total >= 100 ? 'Gratis' : 'S/. 10.00'; ?></span>
                        </div>
                        
                        <?php if ($cart_total < 100): ?>
                            <div class="summary-row" style="color: #2563eb; font-size: 0.9rem;">
                                <span>Envío gratis desde S/. 100</span>
                                <span>S/. <?php echo number_format(100 - $cart_total, 2); ?> restantes</span>
                            </div>
                        <?php endif; ?>
                        
                        <div class="summary-total">
                            <span>Total</span>
                            <span>S/. <?php echo number_format($cart_total + ($cart_total >= 100 ? 0 : 10), 2); ?></span>
                        </div>
                        
                        <a href="checkout.php" class="checkout-btn">
                            <span class="material-symbols-rounded">payment</span>
                            Proceder al Checkout
                        </a>
                        
                        <a href="store.php" class="continue-shopping">
                            <span class="material-symbols-rounded">arrow_back</span>
                            Seguir Comprando
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Hidden forms for AJAX updates -->
    <form id="update-quantity-form" method="POST" style="display: none;">
        <input type="hidden" name="action" value="update_quantity">
        <input type="hidden" name="product_id" id="update-product-id">
        <input type="hidden" name="quantity" id="update-quantity">
    </form>

    <script>
        function updateQuantity(productId, change) {
            const input = document.getElementById('qty-' + productId);
            const currentValue = parseInt(input.value) || 1;
            const newValue = Math.max(1, currentValue + change);
            
            input.value = newValue;
            updateQuantityDirect(productId, newValue);
        }

        function updateQuantityDirect(productId, quantity) {
            quantity = Math.max(1, parseInt(quantity) || 1);
            
            document.getElementById('update-product-id').value = productId;
            document.getElementById('update-quantity').value = quantity;
            document.getElementById('update-quantity-form').submit();
        }

        // Auto-submit quantity changes after a delay
        let quantityTimeout;
        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('input', function() {
                clearTimeout(quantityTimeout);
                const productId = this.id.replace('qty-', '');
                const quantity = this.value;
                
                quantityTimeout = setTimeout(() => {
                    updateQuantityDirect(productId, quantity);
                }, 1000);
            });
        });
    </script>
</body>
</html>
