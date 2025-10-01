<?php
session_start();
include_once("conexionSQL/conexion.php");

$cart = $_SESSION['cart'] ?? [];
function cart_total($cart){ $t=0; foreach($cart as $it){ $t += ((float)$it['precio'])*((int)$it['cantidad']); } return $t; }

$successMsg = '';
$errorMsg = '';
$paymentRef = '';

// Validar que el usuario esté autenticado
if (!isset($_SESSION['cliente_id']) || empty($_SESSION['cliente_id'])) {
    header('Location: login.php?redirect=checkout');
    exit;
}

// Validar que el carrito no esté vacío
if (empty($cart)) {
    header('Location: carro.php');
    exit;
}

// Get user data
$user_id = $_SESSION['cliente_id'];
$stmt = $conexion->prepare("SELECT * FROM clientes WHERE id_cliente = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['pay_method'])) {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $address = trim($_POST['address'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $method = $_POST['pay_method'];

    if ($name==='' || $email==='' || $address==='') {
        $errorMsg = 'Completa todos los campos obligatorios.';
    } else {
        // Calculate totals
        $subtotal = cart_total($cart);
        $shipping = $subtotal >= 100 ? 0 : 10;
        $total = $subtotal + $shipping;
        
        // Create order
        $fecha = date('Y-m-d H:i:s');
        $stmt = $conexion->prepare("INSERT INTO venta (id_cliente, fecha, total, metodo_pago, estado) VALUES (?, ?, ?, ?, 'completada')");
        $stmt->bind_param("isds", $user_id, $fecha, $total, $method);
        
        if ($stmt->execute()) {
            $venta_id = $conexion->insert_id;
            
            // Insert order details
            $stmt_detail = $conexion->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)");
            
            foreach ($cart as $item) {
                $subtotal_item = $item['precio'] * $item['cantidad'];
                $stmt_detail->bind_param("iiidd", $venta_id, $item['id'], $item['cantidad'], $item['precio'], $subtotal_item);
                $stmt_detail->execute();
            }
            
            // Clear cart
            $_SESSION['cart'] = [];
            
            // Generate payment reference
            $paymentRef = 'FAB-' . str_pad($venta_id, 6, '0', STR_PAD_LEFT);
            $successMsg = "¡Pedido realizado exitosamente! Tu número de pedido es: $paymentRef";
            
            // Redirect to thank you page after 3 seconds
            echo "<script>setTimeout(function(){ window.location.href = 'thank_you.php?ref=$paymentRef'; }, 3000);</script>";
        } else {
            $errorMsg = 'Error al procesar el pedido. Intenta nuevamente.';
        }
    }
}

$cart_total = cart_total($cart);
$shipping_cost = $cart_total >= 100 ? 0 : 10;
$final_total = $cart_total + $shipping_cost;
$cart_count = 0;
foreach ($cart as $item) {
    $cart_count += $item['cantidad'];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | FABIAN Publicidad</title>
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

        /* Checkout Layout */
        .checkout-layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 40px;
        }

        /* Checkout Form */
        .checkout-form {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            overflow: hidden;
        }

        .form-section {
            padding: 30px;
            border-bottom: 1px solid #f1f5f9;
        }

        .form-section:last-child {
            border-bottom: none;
        }

        .section-title {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
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

        .required {
            color: #dc2626;
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

        /* Payment Methods */
        .payment-methods {
            display: grid;
            gap: 15px;
        }

        .payment-option {
            display: flex;
            align-items: center;
            padding: 16px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f9fafb;
        }

        .payment-option:hover {
            border-color: #2563eb;
            background: rgba(37, 99, 235, 0.05);
        }

        .payment-option.selected {
            border-color: #2563eb;
            background: rgba(37, 99, 235, 0.1);
        }

        .payment-option input[type="radio"] {
            margin-right: 12px;
            accent-color: #2563eb;
        }

        .payment-icon {
            margin-right: 12px;
            font-size: 1.5rem;
        }

        .payment-info {
            flex: 1;
        }

        .payment-name {
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 4px;
        }

        .payment-desc {
            font-size: 0.9rem;
            color: #64748b;
        }

        /* Order Summary */
        .order-summary {
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

        .order-items {
            margin-bottom: 20px;
        }

        .order-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }

        .order-item:last-child {
            border-bottom: none;
        }

        .item-image {
            width: 50px;
            height: 50px;
            object-fit: cover;
            border-radius: 8px;
        }

        .item-details {
            flex: 1;
        }

        .item-name {
            font-weight: 500;
            color: #374151;
            font-size: 0.9rem;
            margin-bottom: 4px;
        }

        .item-quantity {
            font-size: 0.8rem;
            color: #64748b;
        }

        .item-price {
            font-weight: 600;
            color: #2563eb;
            font-size: 0.9rem;
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

        .place-order-btn {
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
        }

        .place-order-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 40px rgba(37, 99, 235, 0.4);
        }

        .place-order-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
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

        /* Security Badge */
        .security-badge {
            display: flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            color: #16a34a;
            padding: 12px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-top: 20px;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .checkout-layout {
                grid-template-columns: 1fr;
                gap: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .order-summary {
                position: static;
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
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <!-- Page Header -->
            <div class="page-header">
                <h1 class="page-title">Finalizar Compra</h1>
                <p class="page-subtitle">Completa tu información para procesar el pedido</p>
            </div>

            <?php if ($errorMsg): ?>
                <div class="alert alert-error">
                    <span class="material-symbols-rounded">error</span>
                    <?php echo htmlspecialchars($errorMsg); ?>
                </div>
            <?php endif; ?>
            
            <?php if ($successMsg): ?>
                <div class="alert alert-success">
                    <span class="material-symbols-rounded">check_circle</span>
                    <?php echo htmlspecialchars($successMsg); ?>
                </div>
            <?php endif; ?>

            <div class="checkout-layout">
                <!-- Checkout Form -->
                <div class="checkout-form">
                    <form method="POST" id="checkout-form">
                        <!-- Shipping Information -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <span class="material-symbols-rounded">local_shipping</span>
                                Información de Envío
                            </h3>
                            
                            <div class="form-grid">
                                <div class="form-group">
                                    <label class="form-label">Nombre Completo <span class="required">*</span></label>
                                    <input type="text" name="name" class="form-input" value="<?php echo htmlspecialchars($user['nombre'] . ' ' . $user['apellidos']); ?>" required>
                                </div>
                                
                                <div class="form-group">
                                    <label class="form-label">Teléfono <span class="required">*</span></label>
                                    <input type="tel" name="phone" class="form-input" value="<?php echo htmlspecialchars($user['telefono'] ?? ''); ?>" required>
                                </div>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Email <span class="required">*</span></label>
                                <input type="email" name="email" class="form-input" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label class="form-label">Dirección de Envío <span class="required">*</span></label>
                                <input type="text" name="address" class="form-input" value="<?php echo htmlspecialchars($user['direccion'] ?? ''); ?>" placeholder="Calle, número, distrito, ciudad" required>
                            </div>
                        </div>

                        <!-- Payment Method -->
                        <div class="form-section">
                            <h3 class="section-title">
                                <span class="material-symbols-rounded">payment</span>
                                Método de Pago
                            </h3>
                            
                            <div class="payment-methods">
                                <label class="payment-option" for="transferencia">
                                    <input type="radio" name="pay_method" value="transferencia" id="transferencia" required>
                                    <span class="payment-icon">🏦</span>
                                    <div class="payment-info">
                                        <div class="payment-name">Transferencia Bancaria</div>
                                        <div class="payment-desc">Pago seguro mediante transferencia</div>
                                    </div>
                                </label>
                                
                                <label class="payment-option" for="yape">
                                    <input type="radio" name="pay_method" value="yape" id="yape" required>
                                    <span class="payment-icon">📱</span>
                                    <div class="payment-info">
                                        <div class="payment-name">Yape</div>
                                        <div class="payment-desc">Pago rápido con Yape</div>
                                    </div>
                                </label>
                                
                                <label class="payment-option" for="plin">
                                    <input type="radio" name="pay_method" value="plin" id="plin" required>
                                    <span class="payment-icon">💳</span>
                                    <div class="payment-info">
                                        <div class="payment-name">Plin</div>
                                        <div class="payment-desc">Pago instantáneo con Plin</div>
                                    </div>
                                </label>
                                
                                <label class="payment-option" for="efectivo">
                                    <input type="radio" name="pay_method" value="efectivo" id="efectivo" required>
                                    <span class="payment-icon">💵</span>
                                    <div class="payment-info">
                                        <div class="payment-name">Pago Contraentrega</div>
                                        <div class="payment-desc">Paga en efectivo al recibir</div>
                                    </div>
                                </label>
                            </div>
                            
                            <div class="security-badge">
                                <span class="material-symbols-rounded">security</span>
                                Tus datos están protegidos con encriptación SSL
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Order Summary -->
                <div class="order-summary">
                    <h3 class="summary-title">
                        <span class="material-symbols-rounded">receipt</span>
                        Resumen del Pedido
                    </h3>
                    
                    <div class="order-items">
                        <?php foreach ($cart as $item): ?>
                            <div class="order-item">
                                <img src="<?php echo htmlspecialchars($item['imagen'] ?: 'assets/logo.png'); ?>" 
                                     alt="<?php echo htmlspecialchars($item['nombre']); ?>" 
                                     class="item-image"
                                     onerror="this.src='assets/logo.png';">
                                <div class="item-details">
                                    <div class="item-name"><?php echo htmlspecialchars($item['nombre']); ?></div>
                                    <div class="item-quantity">Cantidad: <?php echo $item['cantidad']; ?></div>
                                </div>
                                <div class="item-price">S/. <?php echo number_format($item['precio'] * $item['cantidad'], 2); ?></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="summary-row">
                        <span>Subtotal (<?php echo $cart_count; ?> items)</span>
                        <span>S/. <?php echo number_format($cart_total, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span>Envío</span>
                        <span><?php echo $shipping_cost > 0 ? 'S/. ' . number_format($shipping_cost, 2) : 'Gratis'; ?></span>
                    </div>
                    
                    <?php if ($cart_total < 100): ?>
                        <div class="summary-row" style="color: #2563eb; font-size: 0.9rem;">
                            <span>Envío gratis desde S/. 100</span>
                            <span>S/. <?php echo number_format(100 - $cart_total, 2); ?> restantes</span>
                        </div>
                    <?php endif; ?>
                    
                    <div class="summary-total">
                        <span>Total</span>
                        <span>S/. <?php echo number_format($final_total, 2); ?></span>
                    </div>
                    
                    <button type="submit" form="checkout-form" class="place-order-btn" id="place-order">
                        <span class="material-symbols-rounded">shopping_bag</span>
                        Realizar Pedido
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Payment method selection
        document.querySelectorAll('input[name="pay_method"]').forEach(radio => {
            radio.addEventListener('change', function() {
                document.querySelectorAll('.payment-option').forEach(option => {
                    option.classList.remove('selected');
                });
                this.closest('.payment-option').classList.add('selected');
            });
        });

        // Form validation
        document.getElementById('checkout-form').addEventListener('submit', function(e) {
            const requiredFields = this.querySelectorAll('input[required]');
            let isValid = true;
            
            requiredFields.forEach(field => {
                if (!field.value.trim()) {
                    isValid = false;
                    field.style.borderColor = '#dc2626';
                } else {
                    field.style.borderColor = '#e5e7eb';
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor completa todos los campos obligatorios');
                return;
            }
            
            // Disable button to prevent double submission
            const btn = document.getElementById('place-order');
            btn.disabled = true;
            btn.innerHTML = '<span class="material-symbols-rounded">refresh</span> Procesando...';
        });

        // Auto-fill phone if empty
        document.addEventListener('DOMContentLoaded', function() {
            const phoneField = document.querySelector('input[name="phone"]');
            if (!phoneField.value) {
                phoneField.focus();
            }
        });
    </script>
</body>
</html>
