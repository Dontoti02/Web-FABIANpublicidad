<?php
session_start();
include_once("conexionSQL/conexion.php");
include_once("includes/email_functions.php");

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Por favor ingresa tu email';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Por favor ingresa un email válido';
    } else {
        // Verificar si el email existe
        $stmt = $conexion->prepare("SELECT id_cliente, nombre FROM clientes WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Generar token único
            $token = generateRecoveryToken();

            // Guardar token en base de datos
            if (saveRecoveryToken($email, $token)) {
                // Crear enlace de recuperación
                $resetLink = "http://" . $_SERVER['HTTP_HOST'] . "/PROYECTO-G5-ING-WEB/reset_password.php?token=" . $token . "&email=" . urlencode($email);

                // Crear mensaje de email
                $subject = "Recuperación de contraseña - FABIAN Publicidad";
                $message = "
                <!DOCTYPE html>
                <html>
                <head>
                    <meta charset='UTF-8'>
                    <title>Recuperación de Contraseña</title>
                </head>
                <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #333;'>
                    <div style='max-width: 600px; margin: 0 auto; padding: 20px;'>
                        <div style='background: linear-gradient(135deg, #2ECC71, #27AE60); padding: 30px; border-radius: 10px; text-align: center; margin-bottom: 30px;'>
                            <h1 style='color: white; margin: 0; font-size: 24px;'>FABIAN Publicidad</h1>
                            <p style='color: white; margin: 10px 0 0 0;'>Recuperación de Contraseña</p>
                        </div>

                        <div style='background: #f9f9f9; padding: 30px; border-radius: 10px; margin-bottom: 20px;'>
                            <h2 style='color: #2ECC71; margin-top: 0;'>Hola " . htmlspecialchars($user['nombre']) . "</h2>
                            <p>Has solicitado restablecer tu contraseña en FABIAN Publicidad. Para continuar con el proceso, haz clic en el siguiente enlace:</p>

                            <div style='text-align: center; margin: 30px 0;'>
                                <a href='" . $resetLink . "' style='background: #2ECC71; color: white; padding: 15px 30px; text-decoration: none; border-radius: 8px; font-weight: bold; display: inline-block; box-shadow: 0 4px 15px rgba(46, 204, 113, 0.3);'>
                                    Restablecer Contraseña
                                </a>
                            </div>

                            <p style='color: #666; font-size: 14px;'>
                                Este enlace expirará en 1 hora por seguridad.<br>
                                Si no solicitaste este cambio, puedes ignorar este mensaje.
                            </p>

                            <hr style='border: none; border-top: 1px solid #ddd; margin: 20px 0;'>

                            <p style='color: #666; font-size: 12px; text-align: center;'>
                                Si el botón no funciona, copia y pega esta URL en tu navegador:<br>
                                <a href='" . $resetLink . "' style='color: #2ECC71; word-break: break-all;'>" . $resetLink . "</a>
                            </p>
                        </div>

                        <div style='text-align: center; color: #666; font-size: 12px;'>
                            <p>¿Necesitas ayuda? Contáctanos en <a href='mailto:info@miniplus.com' style='color: #2ECC71;'>info@miniplus.com</a></p>
                            <p>&copy; 2025 FABIAN Publicidad. Todos los derechos reservados.</p>
                        </div>
                    </div>
                </body>
                </html>";

                // Enviar email
                if (sendRecoveryEmail($email, $subject, $message)) {
                    $success = 'Se ha enviado un enlace de recuperación a tu email. Revisa tu bandeja de entrada.';
                } else {
                    $error = 'Error al enviar el email. Inténtalo nuevamente.';
                }
            } else {
                $error = 'Error al procesar la solicitud. Inténtalo nuevamente.';
            }
        } else {
            // Por seguridad, no revelamos si el email existe o no
            $success = 'Si tu email está registrado, recibirás un enlace de recuperación en breve.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recuperar Contraseña | FABIAN Publicidad</title>
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
        .recovery-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 450px;
            padding: 0;
        }
        .recovery-header {
            background: linear-gradient(135deg, #2ECC71, #27AE60);
            color: white;
            text-align: center;
            padding: 40px 30px;
        }
        .recovery-header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 700;
        }
        .recovery-header .brand-plus {
            color: #F39C12;
        }
        .recovery-header p {
            margin: 10px 0 0 0;
            opacity: 0.9;
            font-size: 1rem;
        }
        .recovery-body {
            padding: 40px 30px;
        }
        .form-group {
            margin-bottom: 25px;
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
        .btn-recovery {
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
        .btn-recovery:hover {
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
        .recovery-footer {
            text-align: center;
            padding: 0 30px 30px;
            border-top: 1px solid #ecf0f1;
            margin-top: 20px;
            padding-top: 25px;
        }
        .recovery-footer a {
            color: #2ECC71;
            text-decoration: none;
            font-weight: 500;
        }
        .recovery-footer a:hover {
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

    <div class="recovery-container">
        <div class="recovery-header">
            <h1>Mini<span class="brand-plus">+</span></h1>
            <p>Recuperar Contraseña</p>
        </div>

        <div class="recovery-body">
            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fa fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label style="display: block; margin-bottom: 8px; font-weight: 600; color: #333;">
                        <i class="fa fa-envelope"></i> Email
                    </label>
                    <input type="email" name="email" class="form-control" placeholder="Ingresa tu email registrado" required value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>">
                    <small style="color: #666; font-size: 0.9rem;">Ingresa el email con el que te registraste</small>
                </div>

                <button type="submit" class="btn btn-recovery">
                    <i class="fa fa-paper-plane"></i> Enviar Enlace de Recuperación
                </button>
            </form>
        </div>

        <div class="recovery-footer">
            <p><a href="login.php"><i class="fa fa-arrow-left"></i> Volver al inicio de sesión</a></p>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-focus en el campo de email
            const emailField = document.querySelector('input[name="email"]');
            if (emailField) {
                emailField.focus();
            }
        });
    </script>
</body>
</html>
