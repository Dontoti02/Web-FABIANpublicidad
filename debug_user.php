<?php
// Test script to debug user data loading issue
session_start();

// Check if user is logged in
if (!isset($_SESSION['id']) || !isset($_SESSION['tipo'])) {
    echo "Usuario no autenticado<br>";
    echo "Session data: " . print_r($_SESSION, true);
    exit;
}

echo "Usuario autenticado<br>";
echo "Session ID: " . $_SESSION['id'] . "<br>";
echo "Session nombre: " . $_SESSION['nombre'] . "<br>";
echo "Session tipo: " . $_SESSION['tipo'] . "<br>";

// Test database connection
require_once __DIR__ . '/models/ConfiguracionModel.php';
$model = new ConfiguracionModel();

$data = $model->obtenerDatosUsuario($_SESSION['id']);
echo "Datos del usuario desde modelo: " . print_r($data, true);
?>
