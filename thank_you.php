<?php
session_start();
$ref = $_GET['ref'] ?? '';
$order = $_SESSION['last_order'] ?? null;
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gracias por tu compra | FABIAN</title>
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
            max-width: 800px;
            margin: 0 auto;
            padding: 0 20px;
        }

        /* Success Card */
        .success-card {
            background: white;
            border-radius: 16px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            border: 1px solid #f1f5f9;
            padding: 50px;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .success-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981, #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 30px;
            color: white;
            font-size: 2.5rem;
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.3);
        }

        .success-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 15px;
        }

        .success-subtitle {
            font-size: 1.1rem;
            color: #64748b;
            margin-bottom: 30px;
            line-height: 1.6;
        }

        /* Order Details */
        .order-details {
            background: #f8fafc;
            border-radius: 12px;
            padding: 25px;
            margin-bottom: 30px;
            border: 1px solid #f1f5f9;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
        }

        .detail-row:last-child {
            border-bottom: none;
            font-weight: 600;
            font-size: 1.1rem;
            padding-top: 15px;
            margin-top: 10px;
        }

        .detail-label {
            color: #64748b;
            font-weight: 500;
        }

        .detail-value {
            color: #1e293b;
            font-weight: 600;
        }

        .order-reference {
            font-family: 'Courier New', monospace;
            font-size: 1.2rem;
            color: #2563eb;
            font-weight: 700;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 15px;
            justify-content: center;
            margin-top: 30px;
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
            font-size: 0.95rem;
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

        /* Payment Method Badge */
        .payment-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f0fdf4;
            color: #16a34a;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 500;
            margin-top: 15px;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #64748b;
        }

        .empty-state h2 {
            font-size: 1.8rem;
            margin-bottom: 15px;
            color: #1e293b;
        }

        .empty-state p {
            margin-bottom: 25px;
            font-size: 1rem;
        }

        /* Mobile Responsive */
        @media (max-width: 768px) {
            .success-card {
                padding: 30px 20px;
                margin: 0 10px;
            }

            .success-title {
                font-size: 2rem;
            }

            .action-buttons {
                flex-direction: column;
                align-items: center;
            }

            .btn {
                width: 100%;
                max-width: 250px;
                justify-content: center;
            }

            .detail-row {
                flex-direction: column;
                align-items: flex-start;
                gap: 5px;
            }
        }

        /* Animation */
        .success-card {
            animation: fadeInUp 0.6s ease forwards;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
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
            <?php if($order && $ref && $order['ref']===$ref): ?>
                <div class="success-card">
                    <div class="success-icon">
                        <span class="material-symbols-rounded">check_circle</span>
                    </div>

                    <h1 class="success-title">¡Pedido Exitoso!</h1>
                    <p class="success-subtitle">
                        Gracias por elegir FABIAN para tus productos promocionales.<br>
                        Tu pedido ha sido registrado correctamente.
                    </p>

                    <div class="order-details">
                        <div class="detail-row">
                            <span class="detail-label">Número de Pedido</span>
                            <span class="detail-value order-reference"><?php echo htmlspecialchars($order['ref']); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Método de Pago</span>
                            <span class="detail-value"><?php echo strtoupper(htmlspecialchars($order['method'])); ?></span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Estado</span>
                            <span class="detail-value" style="color: #10b981;">Procesando</span>
                        </div>
                        <div class="detail-row">
                            <span class="detail-label">Total Pagado</span>
                            <span class="detail-value" style="color: #2563eb; font-size: 1.3rem;">S/. <?php echo number_format((float)$order['total'], 2); ?></span>
                        </div>
                    </div>

                    <div class="payment-badge">
                        <span class="material-symbols-rounded">security</span>
                        Pago seguro procesado
                    </div>

                    <div class="action-buttons">
                        <a href="store.php" class="btn btn-primary">
                            <span class="material-symbols-rounded">shopping_bag</span>
                            Seguir Comprando
                        </a>
                        <a href="perfil.php" class="btn btn-secondary">
                            <span class="material-symbols-rounded">person</span>
                            Ver Pedidos
                        </a>
                    </div>

                    <p style="margin-top: 25px; font-size: 0.9rem; color: #64748b;">
                        Recibirás un email de confirmación con los detalles de tu pedido.<br>
                        Para cualquier consulta, contáctanos en nuestros canales de atención.
                    </p>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <div style="width: 80px; height: 80px; background: #fee2e2; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px; color: #dc2626; font-size: 2.5rem;">
                        <span class="material-symbols-rounded">error</span>
                    </div>
                    <h2>Información no encontrada</h2>
                    <p>No se pudo encontrar la información de tu pedido. Esto puede ocurrir si:</p>
                    <ul style="text-align: left; max-width: 400px; margin: 0 auto 25px; color: #64748b;">
                        <li>• La página fue recargada</li>
                        <li>• El enlace expiró</li>
                        <li>• Ya realizaste el pago anteriormente</li>
                    </ul>
                    <a href="index.php" class="btn btn-primary">
                        <span class="material-symbols-rounded">home</span>
                        Ir al Inicio
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
