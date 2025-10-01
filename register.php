<?php
session_start();
include_once("conexionSQL/conexion.php");

$error = '';
$success = '';

if ($_POST) {
    $nombre = trim($_POST['nombre']);
    $apellidos = trim($_POST['apellidos'] ?? '');
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm_password = trim($_POST['confirm_password']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    
    // Validaciones
    if (empty($nombre) || empty($apellidos) || empty($email) || empty($password) || empty($confirm_password)) {
        $error = 'Por favor completa todos los campos obligatorios';
    } elseif ($password !== $confirm_password) {
        $error = 'Las contraseñas no coinciden';
    } elseif (strlen($password) < 6) {
        $error = 'La contraseña debe tener al menos 6 caracteres';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'El email no es válido';
    } else {
        // Verificar si el email ya existe
        $sql = "SELECT id_cliente FROM clientes WHERE email = ?";
        $stmt = $conexion->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $error = 'Este email ya está registrado';
        } else {
            // Registrar usuario
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $fecha_registro = date('Y-m-d H:i:s');
            
            $sql = "INSERT INTO clientes (nombre, apellidos, email, password, telefono, direccion, fecha_registro, estado) VALUES (?, ?, ?, ?, ?, ?, ?, '1')";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sssssss", $nombre, $apellidos, $email, $hashed_password, $telefono, $direccion, $fecha_registro);
            
            if ($stmt->execute()) {
                $success = 'Cuenta creada exitosamente. Redirigiendo...';
                echo "<script>setTimeout(function(){ window.location.href = 'login.php'; }, 2000);</script>";
            } else {
                $error = 'Error al crear la cuenta. Intenta nuevamente.';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrarse | FABIAN Publicidad</title>
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
            align-items: flex-start;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow-x: hidden;
            overflow-y: auto;
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

        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            box-shadow: 0 32px 64px rgba(0, 0, 0, 0.15);
            width: 100%;
            max-width: 700px;
            max-height: 90vh;
            padding: 0;
            position: relative;
            z-index: 1;
            border: 1px solid rgba(255, 255, 255, 0.2);
            margin: 20px 0;
            overflow-y: auto;
            overflow-x: hidden;
        }

        .register-header {
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

        .register-subtitle {
            color: #64748b;
            font-size: 1rem;
            margin-bottom: 0;
            font-weight: 400;
        }

        .register-body {
            padding: 20px 50px 50px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
            margin-bottom: 24px;
        }

        .form-group {
            margin-bottom: 24px;
            position: relative;
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

        .register-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(135deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.2rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            margin: 30px 0 20px 0;
            position: relative;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(37, 99, 235, 0.3);
        }

        .register-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .register-btn:hover::before {
            left: 100%;
        }

        .register-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 24px rgba(37, 99, 235, 0.4);
        }

        .register-btn:active {
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

        .register-footer {
            text-align: center;
            padding: 0 50px 40px;
            border-top: 1px solid #e5e7eb;
            margin-top: 20px;
            padding-top: 30px;
        }

        .register-footer a {
            color: #2563eb;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }

        .register-footer a:hover {
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

        .password-strength {
            margin-top: 8px;
            font-size: 0.85rem;
            color: #6b7280;
        }

        .strength-bar {
            height: 4px;
            background: #e5e7eb;
            border-radius: 2px;
            margin-top: 4px;
            overflow: hidden;
        }

        .strength-fill {
            height: 100%;
            transition: all 0.3s ease;
            border-radius: 2px;
        }

        .strength-weak { width: 25%; background: #dc2626; }
        .strength-fair { width: 50%; background: #f59e0b; }
        .strength-good { width: 75%; background: #10b981; }
        .strength-strong { width: 100%; background: #059669; }

        /* Custom scrollbar */
        .register-container::-webkit-scrollbar {
            width: 8px;
        }

        .register-container::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 4px;
        }

        .register-container::-webkit-scrollbar-thumb {
            background: rgba(37, 99, 235, 0.3);
            border-radius: 4px;
            transition: background 0.3s ease;
        }

        .register-container::-webkit-scrollbar-thumb:hover {
            background: rgba(37, 99, 235, 0.5);
        }

        /* Responsive */
        @media (max-width: 640px) {
            .form-row {
                grid-template-columns: 1fr;
                gap: 0;
            }
            
            .register-container {
                margin: 10px;
                border-radius: 20px;
                max-width: 95%;
                max-height: 85vh;
            }
            
            .register-header {
                padding: 30px 25px 15px;
            }
            
            .register-body {
                padding: 15px 25px 35px;
            }
            
            .register-footer {
                padding: 0 25px 30px;
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
    
    <div class="register-container">
        <div class="register-header">
            <div class="logo-container">
                <img src="assets/logo.png" alt="FABIAN Logo">
                <span class="brand-text">FABIAN</span>
            </div>
            <p class="register-subtitle">Crea tu cuenta para productos promocionales</p>
        </div>
        
        <div class="register-body">
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
                <div class="form-row">
                    <div class="form-group">
                        <label for="nombre" class="form-label">Nombres <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="nombre" name="nombre" class="form-control" placeholder="Tu nombre" required value="<?php echo htmlspecialchars($_POST['nombre'] ?? ''); ?>">
                            <span class="material-symbols-rounded input-icon">person</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="apellidos" class="form-label">Apellidos <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="text" id="apellidos" name="apellidos" class="form-control" placeholder="Tus apellidos" required value="<?php echo htmlspecialchars($_POST['apellidos'] ?? ''); ?>">
                            <span class="material-symbols-rounded input-icon">badge</span>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="email" class="form-label">Correo electrónico <span class="required">*</span></label>
                    <div class="input-wrapper">
                        <input type="email" id="email" name="email" class="form-control" placeholder="tu@email.com" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                        <span class="material-symbols-rounded input-icon">email</span>
                    </div>
                </div>
                
                <div class="form-row">
                    <div class="form-group">
                        <label for="password" class="form-label">Contraseña <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="password" name="password" class="form-control" placeholder="Mínimo 6 caracteres" required>
                            <span class="material-symbols-rounded input-icon">lock</span>
                            <button type="button" class="password-toggle" onclick="togglePassword('password', 'password-icon')">
                                <span class="material-symbols-rounded" id="password-icon">visibility</span>
                            </button>
                        </div>
                        <div class="password-strength">
                            <div class="strength-bar">
                                <div class="strength-fill" id="strength-fill"></div>
                            </div>
                            <span id="strength-text">Ingresa una contraseña</span>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="confirm_password" class="form-label">Confirmar contraseña <span class="required">*</span></label>
                        <div class="input-wrapper">
                            <input type="password" id="confirm_password" name="confirm_password" class="form-control" placeholder="Repite tu contraseña" required>
                            <span class="material-symbols-rounded input-icon">lock_reset</span>
                            <button type="button" class="password-toggle" onclick="togglePassword('confirm_password', 'confirm-password-icon')">
                                <span class="material-symbols-rounded" id="confirm-password-icon">visibility</span>
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <div class="input-wrapper">
                        <input type="tel" id="telefono" name="telefono" class="form-control" placeholder="+51 999 999 999" value="<?php echo htmlspecialchars($_POST['telefono'] ?? ''); ?>">
                        <span class="material-symbols-rounded input-icon">phone</span>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="direccion" class="form-label">Dirección</label>
                    <div class="input-wrapper">
                        <input type="text" id="direccion" name="direccion" class="form-control" placeholder="Tu dirección completa" value="<?php echo htmlspecialchars($_POST['direccion'] ?? ''); ?>">
                        <span class="material-symbols-rounded input-icon">location_on</span>
                    </div>
                </div>
                
                <button type="submit" class="register-btn">
                    <span class="material-symbols-rounded" style="vertical-align: middle; margin-right: 8px;">person_add</span>
                    Crear Cuenta
                </button>
                
                <p style="font-size: 0.85rem; color: #6b7280; text-align: center; margin-top: 16px;">
                    <span class="required">*</span> Campos obligatorios
                </p>
            </form>
        </div>
        
        <div class="register-footer">
            <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión aquí</a></p>
        </div>
    </div>

    <script>
        function togglePassword(inputId, iconId) {
            const passwordInput = document.getElementById(inputId);
            const passwordIcon = document.getElementById(iconId);
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.textContent = 'visibility_off';
            } else {
                passwordInput.type = 'password';
                passwordIcon.textContent = 'visibility';
            }
        }

        // Password strength checker
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthFill = document.getElementById('strength-fill');
            const strengthText = document.getElementById('strength-text');
            
            let strength = 0;
            let text = '';
            let className = '';
            
            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;
            
            switch(strength) {
                case 0:
                case 1:
                    text = 'Muy débil';
                    className = 'strength-weak';
                    break;
                case 2:
                    text = 'Débil';
                    className = 'strength-weak';
                    break;
                case 3:
                    text = 'Regular';
                    className = 'strength-fair';
                    break;
                case 4:
                    text = 'Buena';
                    className = 'strength-good';
                    break;
                case 5:
                    text = 'Muy fuerte';
                    className = 'strength-strong';
                    break;
            }
            
            strengthFill.className = 'strength-fill ' + className;
            strengthText.textContent = text;
        });

        // Add smooth focus animations
        document.querySelectorAll('.form-control').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Add loading animation to register button
        document.querySelector('form').addEventListener('submit', function() {
            const btn = document.querySelector('.register-btn');
            btn.innerHTML = '<span class="material-symbols-rounded" style="animation: spin 1s linear infinite;">refresh</span> Creando cuenta...';
            btn.disabled = true;
        });

        // Password confirmation validation
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('password').value;
            const confirmPassword = this.value;
            
            if (confirmPassword && password !== confirmPassword) {
                this.style.borderColor = '#dc2626';
            } else {
                this.style.borderColor = '#e5e7eb';
            }
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
