<?php
require_once __DIR__ . '/config/init.php';
require_once '../core/Controller.php';
require_once '../core/Views.php';
require_once '../core/Conexion.php';
require_once '../core/Query.php';

// Start session only if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Verificar autenticación para rutas del admin (excepto login)
if (!isset($_GET['url']) || strpos($_GET['url'], 'login') === false) {
    if (!isset($_SESSION['tipo']) || !in_array($_SESSION['tipo'], ['admin', 'subadmin'])) {
        header('Location: ' . BASE_URL . 'admin/login');
        exit;
    }
}

$controller = 'Dashboard';
$method = 'index';
$params = '';

$url = !empty($_GET['url']) ? $_GET['url'] : 'Dashboard/index';
$arrUrl = explode('/', $url);

if (!empty($arrUrl[0])) {
    $controller = $arrUrl[0];
}

if (!empty($arrUrl[1])) {
    if ($arrUrl[1] != '') {
        $method = $arrUrl[1];
    }
}

if (!empty($arrUrl[2])) {
    if ($arrUrl[2] != '') {
        for ($i = 2; $i < count($arrUrl); $i++) {
            $params .= $arrUrl[$i] . ',';
        }
        $params = trim($params, ',');
    }
}

$controllerFile = 'controllers/' . $controller . '.php';
if (file_exists($controllerFile)) {
    require_once $controllerFile;
    $controller = new $controller();
    if (method_exists($controller, $method)) {
        $controller->$method($params);
    } else {
        echo 'Método no existe';
    }
} else {
    echo 'Controlador no existe';
}