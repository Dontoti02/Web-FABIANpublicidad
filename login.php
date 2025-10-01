<?php
session_start();
include_once("conexionSQL/conexion.php");

$error = '';
$success = '';

// Obtener parámetro de redirección
$redirect = isset($_GET['redirect']) ? trim($_GET['redirect']) : 'index';

if ($_POST) {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    
    if (empty($email) || empty($password)) {
        $error = 'Por favor completa todos los campos';
    } else {
        $sql = "SELECT * FROM clientes WHERE email = ? AND estado = '1'";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password'])) {
                $_SESSION['cliente_id'] = $user['id_cliente'];
                $_SESSION['cliente_nombre'] = $user['nombre'];
                $_SESSION['cliente_email'] = $user['email'];
                
                // Redirigir según el parámetro
                switch($redirect) {
                    case 'checkout':
                        header("Location: checkout.php");
                        break;
                    case 'store':
                        header("Location: store.php");
                        break;
                    case 'cart':
                        header("Location: carro.php");
                        break;
                    case 'index':
                    default:
                        header("Location: index.php");
                        break;
                }
                exit();
            } else {
                $error = 'Credenciales incorrectas';
            }
        } else {
            $error = 'Usuario no encontrado';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión | FABIAN Publicidad</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Animated background */
        body::before {
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

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 440px;
            padding: 0;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }

        .login-header {
            text-align: center;
            padding: 40px 40px 20px;
            position: relative;
        }

        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin-bottom: 20px;
        }

        .logo-container img {
            height: 50px;
            width: auto;
        }

        .brand-text {
            font-size: 2rem;
            font-weight: 700;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .login-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        .login-body {
            padding: 20px 40px 40px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #374151;
            font-size: 0.95rem;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 16px 20px 16px 50px;
            border: 2px solid #e5e7eb;
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9fafb;
            color: #374151;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
            background: white;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }

        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #9ca3af;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .form-control:focus + .input-icon {
            color: #2563eb;
        }

        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            font-size: 20px;
            transition: color 0.3s ease;
        }

        .password-toggle:hover {
            color: #2563eb;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .login-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .login-btn:hover::before {
            left: 100%;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.4);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .alert {
            padding: 16px 20px;
            border-radius: 12px;
            margin-bottom: 24px;
            font-size: 0.95rem;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .alert-success {
            background: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }

        .login-footer {
            text-align: center;
            padding: 0 40px 40px;
            border-top: 1px solid #e5e7eb;
            margin-top: 20px;
            padding-top: 30px;
        }

        .login-footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .login-footer a:hover {
            color: #1d4ed8;
            text-decoration: underline;
        }

        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 12px 20px;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            transition: all 0.3s ease;
            z-index: 2;
        }

        .back-home:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            text-decoration: none;
            transform: translateX(-5px);
        }

        .forgot-password {
            text-align: center;
            margin-top: 16px;
        }

        .forgot-password a {
            color: #6b7280;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s ease;
        }

        .forgot-password a:hover {
            color: #2563eb;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                margin: 10px;
                border-radius: 20px;
            }
            
            .login-header, .login-body, .login-footer {
                padding-left: 30px;
                padding-right: 30px;
            }
            
            .brand-text {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">
        <span class="material-symbols-rounded">arrow_back</span>
        Volver al inicio
    </a>
    
    <div class="login-container">
        <div class="login-header">
            <div class="logo-container">
                <img src="assets/logo.png" alt="FABIAN Logo">
                <span class="brand-text">FABIAN</span>
            </div>
            <p class="login-subtitle">Productos Promocionales y Publicitarios</p>
        </div>
        
        <div class="login-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <span class="material-symbols-rounded">error</span>
                    <?php echo $error; ?>
                </div>
            <?php endif; ?>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <span class="material-symbols-rounded">check_circle</span>
                    <?php echo $success; ?>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico</label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" class="form-control" placeholder="tu@email.com" required>
                        <span class="material-symbols-rounded input-icon">email</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="password" class="form-label">Contraseña</label>
                    <div class="input-wrapper">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Tu contraseña" required>
                        <span class="material-symbols-rounded input-icon">lock</span>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <span class="material-symbols-rounded" id="password-icon">visibility</span>
                        </button>
                    </div>
                </div>
                
                <button type="submit" class="login-btn">
                    Iniciar Sesión
                </button>
                
                <div class="forgot-password">
                    <a href="forgot_password.php">¿Olvidaste tu contraseña?</a>
                </div>
            </form>
        </div>
        
        <div class="login-footer">
            <p>¿No tienes cuenta? <a href="register.php">Regístrate aquí</a></p>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('password-icon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                passwordIcon.textContent = 'visibility';
            }
        }

        // Add smooth focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add loading animation to login button
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.login-btn');
            btn.innerHTML = '<span class="material-symbols-rounded" style="animation: spin 1s linear infinite;">refresh</span> Iniciando sesión...';
            btn.disabled = true;
        });
    </script>

    <style>
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</body>
</html>
