<?php
// Función para enviar emails usando Mailpit
function sendRecoveryEmail($to, $subject, $message) {
    $headers = [
        'MIME-Version: 1.0',
        'Content-type: text/html; charset=UTF-8',
        'From: FABIAN Publicidad <noreply@miniplus.com>',
        'Reply-To: FABIAN Publicidad <noreply@miniplus.com>'
    ];

    $headersStr = '';
    foreach ($headers as $header) {
        $headersStr .= $header . "\r\n";
    }

    // Configuración para Mailpit (localhost:8025)
    ini_set('SMTP', 'localhost');
    ini_set('smtp_port', '1025');

    return mail($to, $subject, $message, $headersStr);
}

// Función para generar token único
function generateRecoveryToken() {
    return bin2hex(random_bytes(32));
}

// Función para verificar si el token es válido
function verifyRecoveryToken($token, $email) {
    global $conexion;

    $stmt = $conexion->prepare("SELECT id_cliente FROM clientes WHERE email = ? AND recovery_token = ? AND token_expiry > NOW()");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $result = $stmt->get_result();

    return $result->num_rows > 0 ? $result->fetch_assoc()['id_cliente'] : false;
}

// Función para guardar token de recuperación
function saveRecoveryToken($email, $token) {
    global $conexion;

    // Token válido por 1 hora
    $expiry = date('Y-m-d H:i:s', strtotime('+1 hour'));

    $stmt = $conexion->prepare("UPDATE clientes SET recovery_token = ?, token_expiry = ? WHERE email = ?");
    $stmt->bind_param("sss", $token, $expiry, $email);

    return $stmt->execute();
}

// Función para actualizar contraseña
function updatePassword($userId, $newPassword) {
    global $conexion;

    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $stmt = $conexion->prepare("UPDATE clientes SET password = ?, recovery_token = NULL, token_expiry = NULL WHERE id_cliente = ?");
    $stmt->bind_param("si", $hashedPassword, $userId);

    return $stmt->execute();
}
?>
