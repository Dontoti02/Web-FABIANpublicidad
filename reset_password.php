<?php
session_start();
include_once("conexionSQL/conexion.php");
include_once("includes/email_functions.php");

$error = '';
$success = '';
$token = $_GET['token'] ?? '';
$email = $_GET['email'] ?? '';

if (empty($token) || empty($email)) {
    header('Location: login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newPassword = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if (empty($newPassword) || empty($confirmPassword)) {
        $error = 'Por favor completa todos los campos';
    } elseif (strlen($newPassword) < 8) {
        $error = 'La contraseña debe tener al menos 8 caracteres';
    } elseif ($newPassword !== $confirmPassword) {
        $error = 'Las contraseñas no coinciden';
    } else {
        // Verificar token
        $userId = verifyRecoveryToken($token, $email);

        if ($userId) {
            // Actualizar contraseña
            if (updatePassword($userId, $newPassword)) {
                $success = 'Tu contraseña ha sido actualizada exitosamente. Ya puedes iniciar sesión con tu nueva contraseña.';
            } else {
                $error = 'Error al actualizar la contraseña. Inténtalo nuevamente.';
            }
        } else {
            $error = 'El enlace de recuperación es inválido o ha expirado.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña | FABIAN Publicidad</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,1,0" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .reset-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            padding: 0;
        }
        .reset-header {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            text-align: center;
            padding: 40px 30px;
        }
        .reset-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .reset-header .brand-plus {
            color: #F39C12;
        }
        .reset-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }
        .reset-body {
            padding: 40px 30px;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-group label {
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            border: 2px solid #ecf0f1;
            border-radius: 12px;
            padding: 15px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }
        .form-control:focus {
            border-color: #2ECC71;
            box-shadow: 0 0 0 3px rgba(46, 204, 113, 0.1);
            background: white;
        }
        .password-wrapper {
            position: relative;
        }
        .password-wrapper .form-control {
            padding-right: 45px;
        }
        .toggle-password {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #7f8c8d;
            cursor: pointer;
            font-size: 18px;
            outline: none;
            padding: 0 5px;
        }
        .btn-reset {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            border: none;
            border-radius: 12px;
            padding: 15px;
            font-size: 1.1rem;
            font-weight: 600;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(46, 204, 113, 0.3);
            color: white;
        }
        .alert {
            border-radius: 12px;
            border: none;
            padding: 15px 20px;
            margin-bottom: 25px;
        }
        .alert-danger {
            background: #fee;
            color: #c33;
        }
        .alert-success {
            background: #efe;
            color: #363;
        }
        .reset-footer {
            text-align: center;
            padding: 0 30px 30px;
            border-top: 1px solid #ecf0f1;
            margin-top: 20px;
            padding-top: 25px;
        }
        .reset-footer a {
            color: #2ECC71;
            text-decoration: none;
            font-weight: 500;
        }
        .reset-footer a:hover {
            color: #27AE60;
            text-decoration: none;
        }
        .back-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 1.2rem;
            text-decoration: none;
            opacity: 0.8;
            transition: opacity 0.3s;
        }
        .back-home:hover {
            opacity: 1;
            color: white;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <a href="index.php" class="back-home">
        <i class="fa fa-arrow-left"></i> Volver al inicio
    </a>

    <div class="reset-container">
        <div class="reset-header">
            <h1>Mini<span class="brand-plus">+</span></h1>
            <p>Nueva Contraseña</p>
        </div>

        <div class="reset-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> <?php echo $success; ?>
                    <br><br>
                    <a href="login.php" class="btn btn-reset" style="margin-top: 15px;">
                        <i class="fa fa-sign-in"></i> Ir al Inicio de Sesión
                    </a>
                </div>
            <?php else: ?>
                <form method="POST">
                    <div class="form-group">
                        <label>Nueva Contraseña *</label>
                        <div class="password-wrapper">
                            <input type="password" name="password" id="password" class="form-control" placeholder="Ingresa tu nueva contraseña" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('password')">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                        <small style="color: #666; font-size: 0.9rem;">Mínimo 8 caracteres</small>
                    </div>

                    <div class="form-group">
                        <label>Confirmar Contraseña *</label>
                        <div class="password-wrapper">
                            <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Confirma tu nueva contraseña" required minlength="8">
                            <button type="button" class="toggle-password" onclick="togglePassword('confirm_password')">
                                <i class="fa fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-reset">
                        <i class="fa fa-save"></i> Actualizar Contraseña
                    </button>
                </form>
            <?php endif; ?>
        </div>

        <div class="reset-footer">
            <p><a href="login.php"><i class="fa fa-arrow-left"></i> Volver al inicio de sesión</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus en el primer campo
            const passwordField = document.getElementById('password');
            if (passwordField) {
                passwordField.focus();
            }
        });

        function togglePassword(fieldId) {
            const field = document.getElementById(fieldId);
            const icon = field.nextElementSibling.querySelector('i');

            if (field.type === 'password') {
                field.type = 'text';
                icon.className = 'fa fa-eye-slash';
            } else {
                field.type = 'password';
                icon.className = 'fa fa-eye';
            }
        }
    </script>
</body>
</html>
