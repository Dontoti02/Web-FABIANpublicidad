<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title; ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f8fafc;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
        }
        
        .login-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 48px 40px;
            width: 100%;
            max-width: 400px;
            border: 1px solid #e2e8f0;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 32px;
        }
        
        .logo-icon {
            width: 48px;
            height: 48px;
            background: #3b82f6;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }
        
        .logo-icon svg {
            width: 24px;
            height: 24px;
            color: white;
        }
        
        .logo h1 {
            font-size: 24px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 8px;
        }
        
        .logo p {
            font-size: 14px;
            color: #64748b;
            font-weight: 400;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-label {
            display: block;
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        
        .form-input {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: white;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .btn-login {
            width: 100%;
            padding: 12px 16px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s ease;
            margin-top: 8px;
        }
        
        .btn-login:hover {
            background: #2563eb;
        }
        
        .btn-login:disabled {
            background: #9ca3af;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        
        .alert-danger {
            background: #fef2f2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }
        
        .alert-warning {
            background: #fffbeb;
            color: #d97706;
            border: 1px solid #fed7aa;
        }
        
        .spinner {
            display: inline-block;
            width: 16px;
            height: 16px;
            border: 2px solid transparent;
            border-top: 2px solid currentColor;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-right: 8px;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        @media (max-width: 480px) {
            .login-container {
                margin: 20px;
                padding: 32px 24px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <div class="logo-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <h1>Admin</h1>
            <p>Acceso al panel de administración</p>
        </div>

        <div id="alert-container"></div>

        <form id="login-form">
            <div class="form-group">
                <label for="correo" class="form-label">Email</label>
                <input type="email" id="correo" name="correo" class="form-input" placeholder="admin@ejemplo.com" required>
            </div>

            <div class="form-group">
                <label for="clave" class="form-label">Contraseña</label>
                <input type="password" id="clave" name="clave" class="form-input" placeholder="••••••••" required>
            </div>

            <button type="submit" class="btn-login">
                Iniciar sesión
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('login-form');
            const alertContainer = document.getElementById('alert-container');
            const submitBtn = form.querySelector('.btn-login');

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(form);
                
                // Show loading state
                submitBtn.innerHTML = '<span class="spinner"></span>Verificando...';
                submitBtn.disabled = true;

                fetch('<?php echo BASE_URL; ?>admin/login/authenticate', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.type === 'success') {
                        showAlert(data.msg, 'success');
                        setTimeout(() => {
                            window.location.href = data.url;
                        }, 1000);
                    } else {
                        showAlert(data.msg, data.type);
                        resetButton();
                    }
                })
                .catch(error => {
                    showAlert('Error de conexión', 'danger');
                    resetButton();
                });
            });

            function resetButton() {
                submitBtn.innerHTML = 'Iniciar sesión';
                submitBtn.disabled = false;
            }

            function showAlert(message, type) {
                alertContainer.innerHTML = `
                    <div class="alert alert-${type}">
                        ${message}
                    </div>
                `;

                setTimeout(() => {
                    alertContainer.innerHTML = '';
                }, 5000);
            }
        });
    </script>
</body>
</html>
