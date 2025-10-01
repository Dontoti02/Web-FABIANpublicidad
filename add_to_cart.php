<?php
session_start();
include_once("conexionSQL/conexion.php");

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Método no permitido']);
    exit;
}

$productId = isset($_POST['product_id']) ? intval($_POST['product_id']) : (isset($_POST['id_producto']) ? intval($_POST['id_producto']) : 0);
$quantity  = isset($_POST['quantity']) ? intval($_POST['quantity']) : (isset($_POST['cantidad']) ? intval($_POST['cantidad']) : 1);
if ($quantity < 1) { $quantity = 1; }

// Validar que el usuario esté autenticado
if (!isset($_SESSION['cliente_id']) || empty($_SESSION['cliente_id'])) {
    echo json_encode(['success' => false, 'message' => 'Debes iniciar sesión para agregar productos al carrito', 'redirect' => 'login.php']);
    exit;
}

if ($productId <= 0) {
    echo json_encode(['success' => false, 'message' => 'Producto inválido']);
    exit;
}

// Obtener producto
$stmt = $conexion->prepare("SELECT id_producto, nombre, precio, stock, estado, imagen FROM productos WHERE id_producto = ? LIMIT 1");
$stmt->bind_param("i", $productId);
$stmt->execute();
$res = $stmt->get_result();
$product = $res ? $res->fetch_assoc() : null;

if (!$product || $product['estado'] != '1') {
    echo json_encode(['success' => false, 'message' => 'Producto no disponible']);
    exit;
}

if ((int)$product['stock'] < $quantity) {
    echo json_encode(['success' => false, 'message' => 'Stock insuficiente']);
    exit;
}

if (!isset($_SESSION['cart'])) { $_SESSION['cart'] = []; }
$cart =& $_SESSION['cart'];

$key = (string)$product['id_producto'];
if (!isset($cart[$key])) {
    $cart[$key] = [
        'id' => (int)$product['id_producto'],
        'nombre' => $product['nombre'],
        'precio' => (float)$product['precio'],
        'imagen' => $product['imagen'] ?? '',
        'cantidad' => 0
    ];
}
$cart[$key]['cantidad'] += $quantity;

// calcular conteo
$count = 0; foreach ($cart as $it) { $count += (int)$it['cantidad']; }

echo json_encode(['success' => true, 'message' => 'Producto agregado al carrito', 'cartCount' => $count]);
